<?php

namespace App\Core;

use Exception;

class ViewNotFoundException extends Exception
{

  public function msg()
  {

    return "View file don't exists";
  }
}
