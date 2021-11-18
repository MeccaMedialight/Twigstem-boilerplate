<?php


define('TWIGSTEM_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Set project directory path
|--------------------------------------------------------------------------
|
| This file needs to know where everything is (the 'project path'). Typically
| this may be
|
| [$projectDir is a sibling]
|   /public_html
|   /project
|       /src ...etc
|       /vendor
|       package.json etc
|
| or
|
| [$projectDir is a parent]
|   /project
|       /public_html
|       /src
|       /vendor
|       package.json etc
|

|
*/
// see if there is a config with the path to application (directory containing views and data)
$projectDir = '';
$path = dirname(__FILE__) . DIRECTORY_SEPARATOR . '.env';
if(file_exists($path)) {
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {

        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);

        if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
            putenv(sprintf('%s=%s', $name, $value));
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}
$projectDir = getenv('TWIGSTEM_DIR');
if (!$projectDir) {
    //assume parent
    $projectDir = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR;
} else {
    // ensure it ends with directory seperator
    $projectDir = rtrim($projectDir, '/') . DIRECTORY_SEPARATOR;
}
/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| this application. We just need to utilize it! We'll simply require it
| into the script here so we don't need to manually load our classes.
|
*/

require $projectDir . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request using
| the application's HTTP kernel. Then, we will send the response back
| to this client's browser, allowing them to enjoy our application.
|
*/

$appDir = $projectDir .'src'.DIRECTORY_SEPARATOR;
$Twigstem = \Twigstem\Server::getInstance();
$Twigstem->init($appDir)->serve();


