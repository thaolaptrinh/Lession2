<?php
require_once 'bootstrap.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

use App\Core\Route as Router;
use App\Core\View;

$router = new Router();

$router
  ->get('/', function () {
    return View::make('app', 'home/index');
  })
  ->get('/products', function () {
    return View::make('app', 'products/index');
  })
  ->get('/categories', [App\Controllers\CategoryController::class, 'index'])
  ->get('/categories/data-html', [App\Controllers\CategoryController::class, 'generateDataCategoriesHTML'])
  ->post('/categories/store', [App\Controllers\CategoryController::class, 'store'])
  ->get('/categories/show', [App\Controllers\CategoryController::class, 'show'])
  ->post('/categories/update', [App\Controllers\CategoryController::class, 'update'])
  ->post('/categories/delete', [App\Controllers\CategoryController::class, 'delete']);


echo $router->resolve($_SERVER['PATH_INFO'] ?? '/', strtolower($_SERVER['REQUEST_METHOD']));
