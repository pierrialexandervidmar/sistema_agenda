<?php
namespace App\Repository;

use App\Entity\Pessoa;
use Doctrine\ORM\EntityManagerInterface;

class PessoaRepository
{
  public EntityManagerInterface $entityManager;

  public function __construct(EntityManagerInterface $entityManager)
  {
      $this->entityManager = $entityManager;
  }
}