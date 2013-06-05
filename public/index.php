<?php
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

define('REQUEST_MICROTIME', microtime(true));
define('SERVER_URL', 'http://'.$_SERVER['HTTP_HOST']);

// Setup autoloading
require 'init_autoloader.php';
include 'module/Application/src/functions.php';

// Run the application!
Zend\Mvc\Application::init(require 'config/application.config.php')->run();
