<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController
{
  public function index(Request $request)
  {
    $viewPath = __DIR__ . '/../Views/home.php';


    if (file_exists($viewPath))
    {
      ob_start();
      include $viewPath;
      $content = ob_get_clean();
    }
    else
    {
      $content = "Página não encontrada.";
    }

    return new Response($content);
  }
}
