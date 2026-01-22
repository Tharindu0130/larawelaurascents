<?php
// Enable all error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

echo "Debug: Starting application<br>";

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

echo "Debug: Maintenance check passed<br>";

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

echo "Debug: Autoloader loaded<br>";

// Bootstrap Laravel and handle the request...
/** @var \Illuminate\Foundation\Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

echo "Debug: App booted<br>";

$request = Request::capture();
echo "Debug: Request captured<br>";

$response = $app->handle($request);
echo "Debug: Request handled, sending response<br>";

$response->send();
$app->terminate($request, $response);