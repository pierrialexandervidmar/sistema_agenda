<?php
// Container.php

namespace App;

use Doctrine\ORM\EntityManager;
use App\Repository\PessoaRepository;
use App\Service\PessoaService;

/**
 * O Container é responsável por gerenciar e fornecer instâncias dos serviços utilizados na aplicação.
 * Ele utiliza o padrão de injeção de dependência para garantir que as classes tenham acesso a
 * suas dependências necessárias, como repositórios e serviços, facilitando a manutenção e testabilidade
 * da aplicação.
 * 
 * @package App
 * @author Pierri Alexander Vidmar
 * @since 10/2024
 */
class Container
{
    private $services = [];

    /**
     * Construtor.
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->services['EntityManager'] = $entityManager;

        // PESSOA
        $this->services['PessoaRepository'] = new PessoaRepository($entityManager);
        $this->services['PessoaService'] = new PessoaService($this->services['PessoaRepository']);

        // INSIRA AS DEMAIS ABAIXO
    }

    /**
     * Obtém uma instância de um serviço registrado no Container.
     *
     * @param string $service O nome do serviço a ser obtido.
     * @return mixed|null Retorna a instância do serviço se encontrado, ou null caso contrário.
     */
    public function get($service)
    {
        return $this->services[$service] ?? null;
    }
}
