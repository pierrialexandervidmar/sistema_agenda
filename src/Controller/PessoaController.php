<?php

namespace App\Controller;

use App\Service\PessoaService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PessoaController
{

  private PessoaService $pessoaService;

  public function __construct(PessoaService $pessoaService)
  {
    $this->pessoaService = $pessoaService;
  }


  public function criar()
  {
    $resultado = ['Nome' => 'Pierre', 'Sobrenome' => 'Alexander'];
    return new JsonResponse($resultado, Response::HTTP_OK);
  }


  public function listar()
  {
    $pessoas = $this->pessoaService->listarPessoas();

    $resultado = [];

    foreach ($pessoas as $pessoa)
    {
      $resultado[] = [
        'id' => $pessoa['id'],
        'nome' => $pessoa['nome'],
        'cpf' => $pessoa['cpf']
      ];
    }

    return new JsonResponse($resultado, Response::HTTP_OK);
  }
}
