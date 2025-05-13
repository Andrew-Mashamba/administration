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
        exec('docker --version', $output, $returnCode);
        if ($returnCode !== 0) {
            throw new Exception("Docker is not installed or not accessible. Please install Docker Desktop and ensure it's running.");
        }
        Log::info("Docker version: " . implode("\n", $output));

        // Check if Docker daemon is running
        exec('docker info', $output, $returnCode);
        if ($returnCode !== 0) {
            throw new Exception("Docker daemon is not running. Please start Docker Desktop.");
        }
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
                //Log::info("Copying files... {$progress}% complete");
            }
        }

        Log::info("Directory copy completed: {$totalFiles} files copied");
    }

    public function provisionWithDocker($alias, $dbName, $dbHost, $dbUser = 'postgres', $dbPassword = 'postgres')
    {
        try {
            set_time_limit(0);

            if (empty($alias) || empty($dbName) || empty($dbHost)) {
                throw new Exception("Alias, database name, and database host are required");
            }

            $this->checkDockerInstallation();

            $alias = Str::slug($alias);
            $dbName = Str::slug($dbName, '_');

            Log::info("Starting provisioning process for alias: {$alias}, database: {$dbName} on host: {$dbHost}");

            $baseDir = dirname(base_path());
            $instancesDir = "{$baseDir}/instances";
            $baseTemplate = "{$baseDir}/template_v3";
            $dockerDir = "{$baseDir}/docker";
            $targetPath = "{$instancesDir}/{$alias}";

            foreach ([$instancesDir, $dockerDir] as $dir) {
                if (!File::exists($dir)) {
                    File::makeDirectory($dir, 0755, true);
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
    npm

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install pdo pdo_pgsql pgsql gd mbstring zip exif pcntl bcmath

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
RUN chown -R www-data:www-data /var/www/html/storage
RUN chown -R www-data:www-data /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage
RUN chmod -R 775 /var/www/html/bootstrap/cache

# Build frontend assets
RUN npm install
RUN npm run build

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
DOCKERFILE;

            File::put($dockerFilePath, $dockerfileContent);
            Log::info("Dockerfile created at: {$dockerFilePath}");

            Log::info("Step 5: Creating docker-compose file");
            $port = rand(8100, 8999);
            $compose = <<<YML
services:
  {$alias}_app:
    build:
      context: D:/PROJECTS2025/LARAVEL/nbc_saccos/instances/{$alias}
      dockerfile: D:/PROJECTS2025/LARAVEL/nbc_saccos/docker/Dockerfile
    container_name: {$alias}_app
    volumes:
      - D:/PROJECTS2025/LARAVEL/nbc_saccos/instances/{$alias}:/var/www/html
    environment:
      - APACHE_DOCUMENT_ROOT=/var/www/html/public
    ports:
      - "{$port}:80"
    networks:
      - saccos_net

networks:
  saccos_net:
    driver: bridge
YML;

            $composePath = "{$dockerDir}/docker-compose.{$alias}.yml";
            File::put($composePath, $compose);
            Log::info("Docker compose file created at: {$composePath}");

            Log::info("Step 6: Starting Docker container");
            putenv('COMPOSE_BAKE=true');
            //$dockerCommand = 'docker compose -f "' . $composePath . '" up -d --build';
            $dockerCommand  = "docker compose -f " . escapeshellarg($composePath) . " up -d";



            $scriptPath = "{$dockerDir}/run_docker.sh";
$scriptContent = <<<BASH
#!/bin/bash
docker compose -f docker/docker-compose.{$alias}.yml up -d
BASH;

File::put($scriptPath, $scriptContent);
chmod($scriptPath, 0755);

// Execute the script
$upCommand = "bash {$scriptPath}";
exec($upCommand, $output, $returnCode);



            //exec($dockerCommand, $output, $returnCode);
            if ($returnCode !== 0) {
                $errorOutput = implode("\n", $output);
                Log::error("Docker command failed", [
                    'command' => $dockerCommand,
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