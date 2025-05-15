<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use PDO;
use Exception;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class SaccoProvisioner
{
    private $dockerLogger;
    private $baseDir;
    private $instancesDir;
    private $dockerDir;

    public function __construct()
    {
        $this->dockerLogger = Log::channel('docker');
        $this->baseDir = dirname(base_path());
        $this->instancesDir = "{$this->baseDir}/instances";
        $this->dockerDir = "{$this->baseDir}/docker";
    }

    private function executeCommand(string $command, array $env = [], bool $throwOnError = true): array
    {
        $process = new Process(explode(' ', $command));
        $process->setEnv($env);
        $process->setTimeout(3600); // 1 hour timeout
        
        try {
            $process->mustRun();
            $output = $process->getOutput();
            $this->dockerLogger->info("Command executed successfully", [
                'command' => $command,
                'output' => $output
            ]);
            
            return [
                'success' => true,
                'output' => $output,
                'exit_code' => $process->getExitCode()
            ];
        } catch (ProcessFailedException $e) {
            $errorOutput = $e->getProcess()->getErrorOutput();
            $this->dockerLogger->error("Command execution failed", [
                'command' => $command,
                'error' => $errorOutput,
                'exit_code' => $e->getProcess()->getExitCode()
            ]);
            
            if ($throwOnError) {
                throw new Exception("Command failed: {$command}. Error: {$errorOutput}");
            }
            
            return [
                'success' => false,
                'output' => $errorOutput,
                'exit_code' => $e->getProcess()->getExitCode()
            ];
        }
    }

    private function checkDockerInstallation(): void
    {
        $this->dockerLogger->info("Checking Docker installation");
        
        // Check Docker installation
        $result = $this->executeCommand('which docker', [], false);
        if (!$result['success']) {
            throw new Exception("Docker is not installed. Please install Docker using: sudo yum install docker-ce docker-ce-cli containerd.io (CentOS) or sudo apt-get install docker-ce docker-ce-cli containerd.io (Ubuntu)");
        }

        // Check Docker daemon
        $result = $this->executeCommand('systemctl is-active docker', [], false);
        if (!$result['success'] || trim($result['output']) !== 'active') {
            throw new Exception("Docker daemon is not running. Please start it using: sudo systemctl start docker");
        }

        // Check Docker Compose
        $result = $this->executeCommand('which docker-compose', [], false);
        if (!$result['success']) {
            throw new Exception("Docker Compose is not installed. Please install it using: sudo curl -L \"https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)\" -o /usr/local/bin/docker-compose && sudo chmod +x /usr/local/bin/docker-compose");
        }

        // Verify Docker functionality
        $result = $this->executeCommand('docker info', [], false);
        if (!$result['success']) {
            throw new Exception("Docker is not functioning properly. Error: {$result['output']}");
        }

        $this->dockerLogger->info("Docker and Docker Compose are properly installed and running");
    }

    private function copyDirectoryWithProgress(string $source, string $destination): void
    {
        set_time_limit(0);
        $this->dockerLogger->info("Starting directory copy from {$source} to {$destination}");

        if (!File::exists($source)) {
            throw new Exception("Source directory not found: {$source}");
        }

        if (!File::exists($destination)) {
            File::makeDirectory($destination, 0755, true);
            $this->dockerLogger->info("Created destination directory: {$destination}");
        }

        // Use rsync if available for better performance
        $result = $this->executeCommand('which rsync', [], false);
        if ($result['success']) {
            $rsyncCommand = "rsync -av --progress {$source}/ {$destination}/";
            $result = $this->executeCommand($rsyncCommand, [], false);
            
            if ($result['success']) {
                $this->dockerLogger->info("Directory copied successfully using rsync");
                return;
            }
            
            $this->dockerLogger->warning("Rsync failed, falling back to PHP copy", [
                'error' => $result['output']
            ]);
        }

        // Fallback to PHP's copy if rsync fails
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        $totalFiles = iterator_count($iterator);
        $currentFile = 0;

        foreach ($iterator as $item) {
            $currentFile++;
            $relativePath = str_replace($source . DIRECTORY_SEPARATOR, '', $item->getPathname());
            $target = $destination . DIRECTORY_SEPARATOR . $relativePath;

            if ($item->isDir()) {
                if (!File::exists($target)) {
                    File::makeDirectory($target, 0755, true);
                }
            } else {
                File::copy($item, $target);
            }

            if ($currentFile % 10 === 0) {
                $progress = round(($currentFile / $totalFiles) * 100, 2);
                Log::info("Copying files... {$progress}% complete");
            }
        }

        $this->dockerLogger->info("Directory copy completed: {$totalFiles} files copied");
    }

    public function provisionWithDocker(string $alias, string $dbName, string $dbHost, string $dbUser = 'postgres', string $dbPassword = 'postgres'): array
    {
        try {
            set_time_limit(0);
            ini_set('memory_limit', '512M');

            if (empty($alias) || empty($dbName) || empty($dbHost)) {
                throw new Exception("Alias, database name, and database host are required");
            }

            $alias = Str::slug($alias);
            $dbName = Str::slug($dbName, '_');

            $this->dockerLogger->info("Starting provisioning process", [
                'alias' => $alias,
                'database' => $dbName,
                'host' => $dbHost
            ]);

            $this->checkDockerInstallation();

            $baseTemplate = "{$this->baseDir}/template";
            $targetPath = "{$this->instancesDir}/{$alias}";

            // Ensure proper permissions
            foreach ([$this->instancesDir, $this->dockerDir] as $dir) {
                if (!File::exists($dir)) {
                    File::makeDirectory($dir, 0755, true);
                    $this->executeCommand("chmod -R 755 {$dir}");
                    $this->dockerLogger->info("Created directory: {$dir}");
                }
            }

            $this->dockerLogger->info("Step 1: Cloning template");
            $this->copyDirectoryWithProgress($baseTemplate, $targetPath);

            $this->dockerLogger->info("Step 2: Creating database");
            $this->createRemoteDatabase($dbHost, $dbName, $dbUser, $dbPassword);

            $this->dockerLogger->info("Step 3: Generating .env file");
            $this->generateEnvFile($targetPath, $dbName, $dbHost, $dbUser, $dbPassword);

            $this->dockerLogger->info("Step 4: Creating Dockerfile");
            $this->createDockerfile($alias, $targetPath);

            $this->dockerLogger->info("Step 5: Creating docker-compose file");
            $port = $this->generateDockerComposeFile($alias, $targetPath);

            $this->dockerLogger->info("Step 6: Starting Docker container");
            $this->startDockerContainer($alias, $port);

            $this->dockerLogger->info("Step 7: Running post-installation commands");
            $this->runPostInstallationCommands($alias);

            $this->dockerLogger->info("Provisioning completed successfully", [
                'alias' => $alias,
                'url' => "http://localhost:{$port}"
            ]);

            return [
                'success' => true,
                'url' => "http://localhost:{$port}",
                'alias' => $alias,
                'port' => $port
            ];

        } catch (Exception $e) {
            $this->dockerLogger->error("Provisioning failed", [
                'alias' => $alias ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Attempt cleanup if possible
            if (isset($alias) && isset($targetPath)) {
                $this->cleanupFailedProvision($alias, $targetPath);
            }
            
            throw $e;
        }
    }

    private function generateEnvFile(string $targetPath, string $dbName, string $dbHost, string $dbUser, string $dbPassword): void
    {
        $envTemplatePath = "{$this->baseDir}/template/.env";
        if (!File::exists($envTemplatePath)) {
            throw new Exception("Template .env file not found at: {$envTemplatePath}");
        }

        $envContent = str_replace(
            [
                'DB_CONNECTION=mysql',
                'DB_DATABASE=laravel',
                'DB_USERNAME=root',
                'DB_PASSWORD=',
                'DB_HOST=127.0.0.1',
                'DB_PORT=3306',
            ],
            [
                'DB_CONNECTION=pgsql',
                "DB_DATABASE={$dbName}",
                "DB_USERNAME={$dbUser}",
                "DB_PASSWORD={$dbPassword}",
                "DB_HOST={$dbHost}",
                'DB_PORT=5432',
            ],
            File::get($envTemplatePath)
        );

        File::put("{$targetPath}/.env", $envContent);
        $this->executeCommand("chmod 644 {$targetPath}/.env");
    }

            Log::info("Step 4: Creating Dockerfile");
            $dockerFilePath = "{$dockerDir}/Dockerfile";
            $dockerfileContent = <<<DOCKERFILE
FROM php:8.2-apache

# Set environment variables to prevent interactive prompts
ENV DEBIAN_FRONTEND=noninteractive

# Install system dependencies with cleanup in single RUN layer to reduce image size
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev \
    libzip-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libbz2-dev \
    libxslt-dev \
    && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y --no-install-recommends nodejs \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Install PHP extensions with error checking
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        bz2 \
        curl \
        fileinfo \
        gd \
        gettext \
        mbstring \
        exif \
        mysqli \
        pdo \
        pdo_mysql \
        pdo_pgsql \
        pdo_sqlite \
        pgsql \
        xsl \
        zip \
        pcntl \
        bcmath \
    && docker-php-source delete

# Install Imagick with cleanup
RUN apt-get update && apt-get install -y --no-install-recommends libmagickwand-dev \
    && pecl install imagick \
    && docker-php-ext-enable imagick \
    && apt-get remove -y libmagickwand-dev \
    && apt-get autoremove -y \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Install Composer with verification
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --version=2.5.8 \
    && chmod +x /usr/local/bin/composer \
    && composer --version

# Enable Apache modules and configure
RUN a2enmod rewrite headers \
    && echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Set working directory
WORKDIR /var/www/html

# Copy application files with proper ownership
COPY --chown=www-data:www-data . .

# Install PHP dependencies with error checking
RUN composer install --no-interaction --optimize-autoloader --no-dev --no-progress --no-scripts \
    || (echo "Composer install failed" && exit 1)

# Set permissions (simplified using find)
RUN find /var/www/html/storage -type d -exec chmod 775 {} \; \
    && find /var/www/html/storage -type f -exec chmod 664 {} \; \
    && find /var/www/html/bootstrap/cache -type d -exec chmod 775 {} \; \
    && find /var/www/html/bootstrap/cache -type f -exec chmod 664 {} \; \
    && chown -R www-data:www-data /var/www/html

# Install and build frontend assets with error checking
RUN if [ -f package.json ]; then \
        npm install --no-audit --no-fund \
        && npm run build --if-present \
        && npm cache clean --force; \
    fi

# Health check
HEALTHCHECK --interval=30s --timeout=3s \
    CMD curl -f http://localhost/ || exit 1

EXPOSE 80

CMD ["apache2-foreground"]
DOCKERFILE;

        $dockerFilePath = "{$this->dockerDir}/Dockerfile";
        File::put($dockerFilePath, $dockerfileContent);
        $this->executeCommand("chmod 644 {$dockerFilePath}");
    }

            Log::info("Step 5: Creating docker-compose file");
            $port = rand(8100, 8999);
            $compose = <<<YML
version: '3.8'

services:
  {$alias}_app:
    build:
      context: {$targetPath}
      dockerfile: {$this->dockerDir}/Dockerfile
    container_name: {$alias}_app
    volumes:
      - {$targetPath}:/var/www/html
    environment:
      - APACHE_DOCUMENT_ROOT=/var/www/html/public
    ports:
      - "{$port}:80"
    networks:
      - saccos_net
    restart: unless-stopped

networks:
  saccos_net:
    driver: bridge
YML;

            $composePath = "{$dockerDir}/docker-compose.{$alias}.yml";
            File::put($composePath, $compose);
            exec("chmod 644 {$composePath}");
            Log::info("Docker compose file created at: {$composePath}");

            Log::info("Step 6: Starting Docker container");
            putenv('COMPOSE_BAKE=true');
            
            // Execute the script with proper path
            $upCommand = "cd {$baseDir} && docker compose -f {$composePath} up -d --build";
            exec($upCommand, $output, $returnCode);

            if ($returnCode !== 0) {
                $errorOutput = implode("\n", $output);
                Log::error("Docker command failed", [
                    'command' => $upCommand,
                    'output' => $errorOutput,
                    'returnCode' => $returnCode
                ]);
                throw new Exception("Failed to start Docker container. Error: {$errorOutput}");
            }
            Log::info("Docker container started successfully");

            Log::info("Step 7: Waiting for services to be ready");
            sleep(15);

            Log::info("Step 8: Running migrations and setting permissions");
            $commands = [
                'composer install --no-interaction --optimize-autoloader',
                'php artisan migrate --force',
                'npm install',
                'npm run build',
                'chown -R www-data:www-data storage bootstrap/cache',
                'chmod -R 775 storage bootstrap/cache'
            ];

        foreach ($commands as $command) {
            $this->executeCommand("docker exec {$alias}_app {$command}");
            $this->dockerLogger->info("Successfully executed: {$command}");
        }
        
        // Additional health check
        $healthCheck = $this->executeCommand(
            "docker exec {$alias}_app curl -I http://localhost",
            [],
            false
        );
        
        if ($healthCheck['success']) {
            $this->dockerLogger->info("Application health check passed");
        } else {
            $this->dockerLogger->warning("Application health check failed", [
                'output' => $healthCheck['output']
            ]);
        }
    }

    private function createRemoteDatabase(string $host, string $dbName, string $user, string $password): void
    {
        $dsn = "pgsql:host={$host};port=5432;dbname=postgres";
        
        try {
            $this->dockerLogger->info("Attempting to connect to PostgreSQL server", ['host' => $host]);
            
            $pdo = new PDO($dsn, $user, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_TIMEOUT => 30
            ]);
            
            $this->dockerLogger->info("Creating database", ['database' => $dbName]);
            $pdo->exec("CREATE DATABASE \"{$dbName}\"");

        } catch (Exception $e) {
            if (Str::contains($e->getMessage(), 'already exists')) {
                $this->dockerLogger->info("Database already exists", ['database' => $dbName]);
                return;
            }
            
            $this->dockerLogger->error("Database creation failed", [
                'error' => $e->getMessage(),
                'host' => $host,
                'database' => $dbName
            ]);
            
            throw $e;
        }
    }

    private function cleanupFailedProvision(string $alias, string $targetPath): void
    {
        $this->dockerLogger->info("Attempting cleanup after failed provision", ['alias' => $alias]);
        
        try {
            // Stop and remove container if it exists
            $this->executeCommand(
                "docker compose -f {$this->dockerDir}/docker-compose.{$alias}.yml down",
                [],
                false
            );
            
            // Remove docker-compose file
            if (File::exists("{$this->dockerDir}/docker-compose.{$alias}.yml")) {
                File::delete("{$this->dockerDir}/docker-compose.{$alias}.yml");
            }
            
            // Remove instance directory if it exists
            if (File::exists($targetPath)) {
                File::deleteDirectory($targetPath);
            }
            
            $this->dockerLogger->info("Cleanup completed", ['alias' => $alias]);
        } catch (Exception $e) {
            $this->dockerLogger->error("Cleanup failed", [
                'alias' => $alias,
                'error' => $e->getMessage()
            ]);
        }
    }
}