<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SetupApacheConfig extends Command
{
    protected $signature = 'apache:setup-config';
    protected $description = 'Set up the Apache configuration script with proper permissions';

    public function handle()
    {
        $scriptPath = '/usr/local/bin/manage-apache-config';
        $scriptContent = <<<BASH
#!/bin/bash

# Check if running as root
if [ "\$EUID" -ne 0 ]; then
    echo "Please run as root"
    exit 1
fi

# Get parameters
ALIAS=\$1
TARGET_PATH=\$2

# Create Apache config
cat > /etc/httpd/conf.d/\${ALIAS}.conf << EOF
<VirtualHost *:80>
    ServerName \${ALIAS}.nbcsaccos.co.tz
    DocumentRoot "\${TARGET_PATH}/public"

    <Directory "\${TARGET_PATH}/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog "/var/log/httpd/\${ALIAS}-error.log"
    CustomLog "/var/log/httpd/\${ALIAS}-access.log" combined
</VirtualHost>
EOF

# Set permissions
chmod 644 /etc/httpd/conf.d/\${ALIAS}.conf

# Reload Apache
systemctl reload httpd
BASH;

        try {
            // Create the script
            if (!File::put($scriptPath, $scriptContent)) {
                $this->error("Failed to create Apache configuration script");
                return 1;
            }

            // Set permissions
            chmod($scriptPath, 0755);

            // Add sudoers entry
            $sudoersEntry = "www-data ALL=(ALL) NOPASSWD: {$scriptPath}";
            $sudoersFile = '/etc/sudoers.d/apache-config';

            if (!File::put($sudoersFile, $sudoersEntry)) {
                $this->error("Failed to create sudoers entry");
                return 1;
            }

            chmod($sudoersFile, 0440);

            $this->info("Apache configuration script set up successfully");
            return 0;
        } catch (\Exception $e) {
            $this->error("Failed to set up Apache configuration: " . $e->getMessage());
            return 1;
        }
    }
}
