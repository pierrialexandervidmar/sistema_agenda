<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PessoaController
{
  public function __construct()
  {
  }


  public function criar()
  {
    $resultado = ['Nome' => 'Pierre', 'Sobrenome' => 'Alexander'];
    return new JsonResponse($resultado, Response::HTTP_OK);
  }

  public function listar()
  {
    $resultado = ['Nome' => 'Pierre', 'Sobrenome' => 'Alexander'];
    return new JsonResponse($resultado, Response::HTTP_OK);
  }
}