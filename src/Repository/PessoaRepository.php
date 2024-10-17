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

    /**
     * Salva uma entidade Pessoa.
     */
    public function salvar(Pessoa $pessoa): void
    {
        $this->entityManager->persist($pessoa);
        $this->entityManager->flush();
    }

    /**
     * Busca todas as entidades Pessoa.
     *
     * @return Pessoa[] Retorna um array de objetos Pessoa
     */
    public function buscarTodos(): array
    {
        return $this->entityManager->getRepository(Pessoa::class)->findAll();
    }

}
