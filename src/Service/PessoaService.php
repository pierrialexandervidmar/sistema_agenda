<?php
namespace App\Service;


use App\Repository\PessoaRepository;


class PessoaService
{
  private PessoaRepository $pessoaRepository;
  public function __construct(PessoaRepository $pessoaRepository)
  {
    $this->pessoaRepository = $pessoaRepository;    
  }
}