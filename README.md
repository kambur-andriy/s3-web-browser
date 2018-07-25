# Amazon S3 Web Browser

S3 Web Browser is a Laravel application which allow working with S3 bucket via a web browser.   


# Installation

1. Check out the latest release from GitHub:
    
    git clone https://github.com/kambur-andriy/s3-web-browser.git
    
2. Configure a Web Server:

    Example configuration for Apache
    
    * Add VirtualHost for new domain
    
        <VirtualHost *:80>

            ServerName s3-browser.com
            DocumentRoot /var/www/html/s3-browser/public
        
            <Directory /var/www/html/s3-browser/public>
                DirectoryIndex index.php
                Options Indexes FollowSymLinks
                AllowOverride All
                Require all granted
            </Directory>
        
            ErrorLog ${APACHE_LOG_DIR}/error.log
            CustomLog ${APACHE_LOG_DIR}/access.log combined
            
        </VirtualHost>

    * Reload Apache Server
    
        service apache2 reload
        
3. Configure S3 Web Browser

    * Go to project folder
    
    * Rename .env.example to .env
    
    * Open .env file and modify your Amazon credentials
    
    
# Usage

This application provide instruments for working with files and folders located on the S3 bucket.
With this browser you have ability to:

 * See files and folders list
 
 * Easy navigate through the bucket
 
 * Get information about files and folders
 
 * Create folders
 
 * Upload new files
 
 * Copy files and folders
 
 * Remove files and folders
 
 * Download files
    
    
  
    
    