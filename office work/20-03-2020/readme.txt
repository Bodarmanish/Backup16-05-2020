************************** 
** LARAVEL VERSION 5.8  **
** PHP VERSION >= 7.1.3 ** 
**************************


**************************************************
** STEPS BY STEPS CHANGES THAT NEED TO BE DONE  **
** TO SETUP LARAVEL FRAMEWORK ON LOCAL          **
**************************************************

STEP 1:     STEP TO SETUP VIRTUAL DOMAIN FOR LOCAL
STEP 1.1:   Edit FILE  ---  C:\xampp\apache\conf\extra\httpd-vhosts.conf AND ADD BELOW CODE AT BOTTOM
            <VirtualHost *:80>
                DocumentRoot "C:/xampp/htdocs"
                ServerName localhost
            </VirtualHost>

            <VirtualHost *:80>
                DocumentRoot "C:/xampp/htdocs/j1app-dvpt/public"
                ServerName j1app.local.com
                SSLEngine on
                SSLCertificateFile "conf/ssl.crt/server.crt"
                SSLCertificateKeyFile "conf/ssl.key/server.key"
                <Directory "C:/xampp/htdocs/j1app-dvpt/public">
                    Options All
                    AllowOverride All
                    Require all granted
                </Directory>
            </VirtualHost>

            <VirtualHost j1app.local.com:443>
                DocumentRoot "C:/xampp/htdocs/j1app-dvpt/public"
                ServerName j1app.local.com
                SSLEngine on
                SSLCertificateFile "conf/ssl.crt/server.crt"
                SSLCertificateKeyFile "conf/ssl.key/server.key"
                <Directory "C:/xampp/htdocs/j1app-dvpt/public">
                    Options All
                    AllowOverride All
                    Require all granted
                </Directory>
            </VirtualHost>

            <VirtualHost *:80>
                DocumentRoot "C:/xampp/htdocs/j1app-dvpt/public"
                ServerName admin.j1app.local.com
                SSLEngine on
                SSLCertificateFile "conf/ssl.crt/server.crt"
                SSLCertificateKeyFile "conf/ssl.key/server.key"
                <Directory "C:/xampp/htdocs/j1app-dvpt/public">
                    Options All
                    AllowOverride All
                    Require all granted
                </Directory>
            </VirtualHost>

            <VirtualHost admin.j1app.local.com:443>
                DocumentRoot "C:/xampp/htdocs/j1app-dvpt/public"
                ServerName admin.j1app.local.com
                SSLEngine on
                SSLCertificateFile "conf/ssl.crt/server.crt"
                SSLCertificateKeyFile "conf/ssl.key/server.key"
                <Directory "C:/xampp/htdocs/j1app-dvpt/public">
                    Options All
                    AllowOverride All
                    Require all granted
                </Directory>
            </VirtualHost>

            <VirtualHost *:80>
                DocumentRoot "C:/xampp/htdocs/j1app-dvpt/public"
                ServerName agency.j1app.local.com
                SSLEngine on
                SSLCertificateFile "conf/ssl.crt/server.crt"
                SSLCertificateKeyFile "conf/ssl.key/server.key"
                <Directory "C:/xampp/htdocs/j1app-dvpt/public">
                    Options All
                    AllowOverride All
                    Require all granted
                </Directory>
            </VirtualHost>

            <VirtualHost agency.j1app.local.com:443>
                DocumentRoot "C:/xampp/htdocs/j1app-dvpt/public"
                ServerName agency.j1app.local.com
                SSLEngine on
                SSLCertificateFile "conf/ssl.crt/server.crt"
                SSLCertificateKeyFile "conf/ssl.key/server.key"
                <Directory "C:/xampp/htdocs/j1app-dvpt/public">
                    Options All
                    AllowOverride All
                    Require all granted
                </Directory>
            </VirtualHost>
 
STEP 1.2:   Edit FILE  ---  C:\Windows\System32\drivers\etc\hosts AND ADD BELOW CODE AT BOTTOM
            127.0.0.1       j1app.local.com /** For Front URL **/
            127.0.0.1       admin.j1app.local.com - /** For Admin URL **/
            127.0.0.1       agency.j1app.local.com - /** For Agency URL **/

STEP 1.2:   Restart XAMPP and RUN XAMPP AS A ADMINISTRATOR

STEP 2:     INSTALL COMPOSER ON YOUR MACHINE (https://getcomposer.org/download/) AND FOLLOW STEP FROM THIS REF. LINK
STEP 3:     OPEN CMD AND SET YOUR PROJECT FOLDER PATH UNTIL j1app PATH
STEP 4:     RUN COMMAND "composer install"
STEP 5:     CHECK .env FILE (IF FILE NOT EXIST THEN UPLOAD IT AND CONFIGURE DATABASE CONNECTION) 
STEP 5.1    HOW To CONFIGURE DATABASE CONNECTION
                - CREATE DATABASE NAME j1app-dvpt
                - IMPORT FILE FROM FOLDER mysqldb/03_12_2019/j1app-dvpt.sql
                - RUN ALL QUERY FROM query_track_2019_20.SQL FILE
STEP 6:     RUN COMMAND "php artisan config:cache"
STEP 7:     RUN YOUR LOCAL DOMAIN (https://j1app.local.com)
 
 
==================================================================================================================

**************************************************
** STEPS BY STEPS CHANGES THAT NEED TO BE DONE  **
** TO SETUP LARAVEL FRAMEWORK ON LIVE SERVER    **
**************************************************

STEP 1:     CREATE SUB DOMAIN (e.g. j1application.itnqa.itndevelopment.com) and This sub domain to point "public" directory.
STEP 2:     ALLOW IN php.ini (WHM main php.ini)
                - allow_url_fopen = On
                - allow_url_include = On
STEP 3:     RUN COMMAND "composer install" (point to project root directory).
STEP 4:     CHECK .env FILE (IF FILE NOT EXIST THEN UPLOAD IT AND CONFIGURE DATABASE CONNECTION)
STEP 5:     RUN COMMAND "php artisan config:cache"
STEP 6:     RUN YOUR DOMAIN (j1application.itnqa.itndevelopment.com)
  
==================================================================================================================

************************************
***   CODING STANDARD Rules      *** 
************************************
Rule 1:     To Create Class name should be written in Capitalize 
            (e.g class ExampleClass)

Rule 2:     The properties of class like method and variables of class should be written in Camel Case 
            (e.g function exampleMethod(){})

Rule 3:     global.php file is created for write global function inside there
            global function should be written like below example.
            (e.g. function example_function() )

Rule 4:     we can define config variables in (config/common.php)
            config variable format should be like below example.
            (e.g. example_variable => "value" )

Rule 5:     Code Structure Example

            class ClassName
            {
                public function methodOne(){
                    if(condition){
                        foreach(){
                            // code
                        }
                    }
                    else{
                        // code
                    }
                }
            }
            ------------------------------------------------------------
            Do:
            ===
            (1)                                         (2)
            if(condition)                               if(condition){
            {                                               foreach(){
                foreach()                                       // code
                {                                           }
                    // code             OR              }
                }                                       else{
            }                                               // code
            else                                        }
            {
                // code
            }
            ------------------------------------------------------------
            Don't:
            ======
            if(condition){
                // code
            }else{         // Don't use this method of parentheses
                // code
            }

==================================================================================================================

**********************************************
***   J1APP API INTEGRATION in Laravel     *** 
**********************************************

Step 1: Open config/j1appapi.php

Step 2: Set URL according to Local, Development, Live URL of API Server

Step 3: Set APIKey and APIUser according to related server

STEP 4: RUN COMMAND "php artisan config:cache"

======================================================================================================================================
COMMON ARTISAN COMMAND

php artisan config:cache
php artisan config:clear
php artisan cache:clear
php artisan key:generate

======================================================================================================================================
Default Database Requirement
----------------------------
Set default values before project going to live

1) Add 'Root Admin' role
2) Add admin with the "Root Admin" role
3) Predefined Permissions groups and permissions


======================================================================================================================================
Instructions for Developer
--------------------------
1) Always define route name while creating new route.
2) Use route name always on every redirection or define url at any place.
3) Always use key name instead of 'ID' at the place where you needed as static code


4) Database queries need to be created manually by developer

roles:
    - Developer have to prepare sql query for add role
    
permission_group:
    - Developer have to prepare sql query for add permission group
    - specify if the permission group is used as menu section

permissions:
    - Developer have to prepare sql query for add permissions
    - Specify if the permission is used for menu item



======================================================================================================================================
Laravel Exception Handling
==========================

1) Create custom Exception class

    Example: 
    location: app\Exceptions\CustomException
    
    class CustomException extends Exception
    {
        public function __construct($message = "Unauthorized Access")
        {
            parent::__construct($message);
        }
    }
    
2) Register your custom exception in laravel exception handler

    Example: 
    location: app\Exceptions\Handler
    
    class Handler extends ExceptionHandler
    {
        public function render($request, Exception $exception)
        {
            if($exception instanceof CustomException)
            {
                return response()->view('errors.error',$data);
            }
            return parent::render($request, $exception);
        }
    }

3) Use custom exception inside the code

    Example:
    
    use App\Exceptions\CustomException;
    
    throw new CustomException("Test Exception Message");
======================================================================================================================================