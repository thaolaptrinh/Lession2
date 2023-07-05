<?php

namespace App\Core;

use App\Core\ViewNotFoundException;

class View
{


  public function __construct(
    protected string $layout,
    protected string $view,
    protected array $params = []
  ) {
  }

  public static function make(string $layout, string $view, array $params = []): static
  {
    return new static($layout, $view, $params);
  }


  public function __get(string $name)
  {
    return $this->params[$name] ?? null;
  }


  public function render(): string
  {
    $viewPath = VIEW_PATH . $this->view . '.php';

    try {
      if (!file_exists($viewPath)) {
        throw new ViewNotFoundException();
      }
    } catch (ViewNotFoundException $th) {
      echo $th->msg();
      die;
    }

    if (!empty($this->params)) {
      extract($this->params);
    }

    ob_start();
    include $viewPath;
    return (string) ob_get_clean();
  }

  public function layout()
  {
    ob_start();
    include "app/layouts/{$this->layout}.php";
    return (string) ob_get_clean();
  }

  public function __toString()
  {
    $layout = $this->layout();
    $main = $this->render();
    return str_replace('{{content}}', $main, $layout);
  }
}
