<?php
ini_set('display_errors', 1);
define('_DIR_ROOT', __DIR__);

$protocol = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
$base_url = $protocol . '://' . $_SERVER['HTTP_HOST'];
$folder = trim(str_replace(($_SERVER['DOCUMENT_ROOT']), '', (__DIR__)), '/');

$base_url .= !empty($folder) ? '/' . $folder : '';
$url = !empty($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/';

define('VIEW_PATH', __DIR__ . '/app/views/');
define('ASSETS_PATH', $base_url . '/assets/');
define('BASE_URL', $base_url . '/');

require_once 'vendor/autoload.php';
