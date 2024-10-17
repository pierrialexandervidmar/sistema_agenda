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

    /**
     * Busca uma entidade Pessoa pelo seu ID.
     *
     * @return Pessoa|null Retorna um objeto Pessoa ou null
     */
    public function buscarPorId(int $id): ?Pessoa
    {
        return $this->entityManager->getRepository(Pessoa::class)->find($id);
    }

    /**
     * Atualiza uma entidade Pessoa.
     */
    public function atualizar(Pessoa $pessoa): void
    {
        $this->entityManager->flush();
    }

    /**
     * Remove uma entidade Pessoa.
     */
    public function deletar(Pessoa $pessoa): void
    {
        $this->entityManager->remove($pessoa);
        $this->entityManager->flush();
    }

    /**
     * Busca uma entidade Pessoa pelo seu CPF.
     *
     * @return Pessoa|null Retorna um objeto Pessoa ou null
     */
    public function buscarPessoaCpf(string $cpf): ?Pessoa
    {
        $qb = $this->entityManager->createQueryBuilder();
        
        return $qb->select('p')
            ->from(Pessoa::class, 'p')
            ->where('p.cpf = :cpf')
            ->setParameter('cpf', $cpf)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Busca Pessoas pelo seu nome.
     *
     * @return Pessoa[] Retorna um array de objetos Pessoa
     */
    public function buscarPessoaNome(string $nome): array
    {
        $qb = $this->entityManager->createQueryBuilder();
        
        return $qb->select('p')
            ->from(Pessoa::class, 'p')
            ->where('p.nome LIKE :nome')
            ->setParameter('nome', '%' . $nome . '%')
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Busca pessoas de acordo com critérios específicos enviados no array de parâmetro
     * @param array $criterios 
     * @return Pessoa[]
     */
    public function buscarPorCriterio(array $criterios): array
    {
        return $this->entityManager->getRepository(Pessoa::class)->findBy($criterios);
    }
}
