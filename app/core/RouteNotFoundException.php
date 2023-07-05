<?php

namespace App\Core;

use Exception;

class RouteNotFoundException extends Exception
{

  public function render()
  {
    http_response_code(404);
    return '404 Not Found';
  }
}
