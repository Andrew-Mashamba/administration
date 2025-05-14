<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use PDO;
use Exception;

class SaccoProvisioner
{
    private function checkDockerInstallation()
    {
        // Check Docker installation
        exec('which docker', $output, $returnCode);
        if ($returnCode !== 0) {
            throw new Exception("Docker is not installed. Please install Docker using: sudo yum install docker-ce docker-ce-cli containerd.io (CentOS) or sudo apt-get install docker-ce docker-ce-cli containerd.io (Ubuntu)");
        }

        // Check Docker daemon
        exec('systemctl is-active docker', $output, $returnCode);
        if ($returnCode !== 0) {
            throw new Exception("Docker daemon is not running. Please start it using: sudo systemctl start docker");
        }

        // Check Docker Compose
        exec('which docker-compose', $output, $returnCode);
        if ($returnCode !== 0) {
            throw new Exception("Docker Compose is not installed. Please install it using: sudo curl -L \"https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)\" -o /usr/local/bin/docker-compose && sudo chmod +x /usr/local/bin/docker-compose");
        }

        Log::info("Docker and Docker Compose are properly installed and running");
    }

    private function copyDirectoryWithProgress($source, $destination)
    {
        set_time_limit(0);

        if (!File::exists($source)) {
            throw new Exception("Source directory not found: {$source}");
        }

        if (!File::exists($destination)) {
            File::makeDirectory($destination, 0755, true);
        }

        // Use rsync if available for better performance
        exec('which rsync', $output, $returnCode);
        if ($returnCode === 0) {
            $rsyncCommand = "rsync -av --progress {$source}/ {$destination}/";
            exec($rsyncCommand, $output, $returnCode);
            if ($returnCode === 0) {
                Log::info("Directory copied successfully using rsync");
                return;
            }
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

        Log::info("Directory copy completed: {$totalFiles} files copied");
    }

    public function provisionWithDocker($alias, $dbName, $dbHost, $dbUser = 'postgres', $dbPassword = 'postgres')
    {
        try {
            set_time_limit(0);
            ini_set('memory_limit', '512M');

            if (empty($alias) || empty($dbName) || empty($dbHost)) {
                throw new Exception("Alias, database name, and database host are required");
            }

            $this->checkDockerInstallation();

            $alias = Str::slug($alias);
            $dbName = Str::slug($dbName, '_');

            Log::info("Starting provisioning process for alias: {$alias}, database: {$dbName} on host: {$dbHost}");

            $baseDir = dirname(base_path());
            $instancesDir = "{$baseDir}/instances";
            $baseTemplate = "{$baseDir}/template";
            $dockerDir = "{$baseDir}/docker";
            $targetPath = "{$instancesDir}/{$alias}";

            // Ensure proper permissions
            foreach ([$instancesDir, $dockerDir] as $dir) {
                if (!File::exists($dir)) {
                    File::makeDirectory($dir, 0755, true);
                    exec("chmod -R 755 {$dir}");
                    Log::info("Created directory: {$dir}");
                }
            }

            Log::info("Step 1: Cloning template from {$baseTemplate} to {$targetPath}");
            $this->copyDirectoryWithProgress($baseTemplate, $targetPath);
            Log::info("Template cloned successfully");

            Log::info("Step 2: Creating database {$dbName} on {$dbHost}");
            $this->createRemoteDatabase($dbHost, $dbName, $dbUser, $dbPassword);

            Log::info("Step 3: Generating .env file");
            $envTemplatePath = "{$baseTemplate}/.env";
            if (!File::exists($envTemplatePath)) {
                throw new Exception("Template .env file not found at: {$envTemplatePath}");
            }
            $envTemplate = File::get($envTemplatePath);
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
                $envTemplate
            );
            File::put("{$targetPath}/.env", $envContent);
            exec("chmod 644 {$targetPath}/.env");
            Log::info(".env file generated successfully");

            Log::info("Step 4: Creating Dockerfile");
            $dockerFilePath = "{$dockerDir}/Dockerfile";
            $dockerfileContent = <<<DOCKERFILE
FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \\
    git \\
    curl \\
    libpng-dev \\
    libonig-dev \\
    libxml2-dev \\
    zip \\
    unzip \\
    libpq-dev \\
    libzip-dev \\
    libjpeg62-turbo-dev \\
    libfreetype6-dev \\
    nodejs \\
    npm \\
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \\
    && docker-php-ext-install -j$(nproc) pdo pdo_pgsql pgsql gd mbstring zip exif pcntl bcmath

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy existing application directory contents
COPY . /var/www/html

# Install dependencies
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage \\
    && chown -R www-data:www-data /var/www/html/bootstrap/cache \\
    && chmod -R 775 /var/www/html/storage \\
    && chmod -R 775 /var/www/html/bootstrap/cache

# Build frontend assets
RUN npm install && npm run build

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
DOCKERFILE;

            File::put($dockerFilePath, $dockerfileContent);
            exec("chmod 644 {$dockerFilePath}");
            Log::info("Dockerfile created at: {$dockerFilePath}");

            Log::info("Step 5: Creating docker-compose file");
            $port = rand(8100, 8999);
            $compose = <<<YML
version: '3.8'

services:
  {$alias}_app:
    build:
      context: {$targetPath}
      dockerfile: {$dockerDir}/Dockerfile
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
                $execCommand = "docker exec {$alias}_app {$command}";
                exec($execCommand, $output, $returnCode);
                if ($returnCode !== 0) {
                    $errorOutput = implode("\n", $output);
                    Log::error("Command failed: {$command}", [
                        'output' => $errorOutput,
                        'returnCode' => $returnCode
                    ]);
                    throw new Exception("Failed to execute command: {$command}. Error: {$errorOutput}");
                }
                Log::info("Successfully executed: {$command}");
            }

            Log::info("Provisioning completed successfully for alias: {$alias}");
            return [
                'success' => true,
                'url' => "http://localhost:{$port}",
                'alias' => $alias,
                'port' => $port
            ];

        } catch (Exception $e) {
            Log::error("Provisioning failed for alias: {$alias}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    private function createRemoteDatabase($host, $dbName, $user, $password)
    {
        $dsn = "pgsql:host={$host};port=5432;dbname=postgres";
        try {
            Log::info("Attempting to connect to PostgreSQL server at {$host}");
            $pdo = new PDO($dsn, $user, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            Log::info("Creating database: {$dbName}");
            $pdo->exec("CREATE DATABASE \"{$dbName}\"");
            Log::info("Database created successfully");

        } catch (Exception $e) {
            if (Str::contains($e->getMessage(), 'already exists')) {
                Log::info("Database {$dbName} already exists, skipping.");
            } else {
                Log::error("Failed to create database", [
                    'error' => $e->getMessage(),
                    'host' => $host,
                    'database' => $dbName
                ]);
                throw $e;
            }
        }
    }
}