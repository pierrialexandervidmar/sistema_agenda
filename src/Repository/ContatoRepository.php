<?php

namespace App\Repository;

use App\Entity\Contato;
use App\Entity\Pessoa;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Esta classe é responsável por gerenciar as operações de persistência dos objetos Contato.
 * Utiliza o EntityManager do Doctrine para realizar operações de CRUD (Criar, Ler, Atualizar, Deletar)
 * em contatos armazenados na base de dados.
 * 
 * @package App\Repository
 * @author Pierri Alexander Vidmar
 * @since 10/2024
 */
class ContatoRepository
{
    private EntityManagerInterface $entityManager;

    /**
     * ContatoRepository constructor.
     * @param EntityManagerInterface $entityManager O EntityManager responsável por gerenciar as operações de persistência.
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Salva ou atualiza um contato na base de dados.
     * @param Contato $contato 
     * @return void
     */
    public function salvar(Contato $contato): void
    {
        $this->entityManager->persist($contato);
        $this->entityManager->flush();
    }

    /**
     * Busca todos os contatos na base de dados.
     * @return Contato[] 
     */
    public function buscarTodos(): array
    {
        return $this->entityManager->getRepository(Contato::class)->findAll();
    }


    /**
     * Busca contatos de acordo com critérios específicos enviados no array de parâmetro
     * @param array $criterios 
     * @return Contato[]
     */ 
    public function buscarContatoPessoa(array $criterios)
    {
        $query = $this->entityManager->createQuery(
            'SELECT c FROM App\Entity\Contato c WHERE c.pessoa = :pessoa'
        );
        $query->setParameter('pessoa', $criterios['pessoa']);

        return $query->getResult();
    }


   /**
    * Busca todas as pessoas.
    * @param int $id
    * @return Pessoa|null
    */
   public function buscarPessoas(): array
   {
       return $this->entityManager->getRepository(Pessoa::class)->findAll();
   }
}
