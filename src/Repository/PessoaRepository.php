<?php

namespace App\Repository;

use App\Entity\Pessoa;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Classe Pessoa Repository
 *
 * Repositório para gerenciar a persistência de entidades Pessoa.
 *
 * @author Pierri Alexander Vidmar
 * @since 10/2024
 */
class PessoaRepository
{
    public EntityManagerInterface $entityManager;


    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Salva uma entidade Pessoa.
     *
     * @param Pessoa $pessoa A entidade Pessoa a ser salva.
     */
    public function salvar(Pessoa $pessoa): void
    {
        $this->entityManager->persist($pessoa);
        $this->entityManager->flush();
    }

    /**
     * Busca todas as entidades Pessoa.
     *
     * @return Pessoa[] Retorna um array de objetos Pessoa.
     */
    public function buscarTodos(): array
    {
        return $this->entityManager->getRepository(Pessoa::class)->findAll();
    }

    /**
     * Busca pelo seu ID.
     *
     * @param int $id O ID da Pessoa a ser buscada.
     * @return Pessoa|null Retorna um objeto Pessoa ou null se não encontrado.
     */
    public function buscarPorId(int $id): ?Pessoa
    {
        return $this->entityManager->getRepository(Pessoa::class)->find($id);
    }

    /**
     * Atualiza uma Pessoa.
     *
     * @param Pessoa $pessoa A entidade Pessoa a ser atualizada.
     */
    public function atualizar(Pessoa $pessoa): void
    {
        $this->entityManager->flush();
    }

    /**
     * Remove uma Pessoa.
     *
     * @param Pessoa $pessoa A entidade Pessoa a ser removida.
     */
    public function deletar(Pessoa $pessoa): void
    {
        $this->entityManager->remove($pessoa);
        $this->entityManager->flush();
    }

    /**
     * Busca pessoas de acordo com critérios específicos enviados no array de parâmetro.
     *
     * @param array $criterios Os critérios de busca.
     * @return Pessoa[] Retorna um array de objetos Pessoa que correspondem aos critérios.
     */
    public function buscarPorCriterio(array $criterios): array
    {
        return $this->entityManager->getRepository(Pessoa::class)->findBy($criterios);
    }
}
