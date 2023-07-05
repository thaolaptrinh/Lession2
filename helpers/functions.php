<?php

if (!function_exists('asset')) {
  function asset($path)
  {
    return ASSETS_PATH . $path;
  }
}

if (!function_exists('route')) {
  function route($path)
  {
    return BASE_URL . $path;
  }
}

if (!function_exists('active_route')) {
  function active_route($routeUrl)
  {
    return trim((BASE_URL . trim($_SERVER['PATH_INFO'] ?? '', '/')), '/') == trim($routeUrl, '/');
  }
}


function check_string($data)
{
  return (trim(htmlspecialchars(addslashes($data))));
}
