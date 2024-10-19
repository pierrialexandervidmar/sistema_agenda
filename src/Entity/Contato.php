<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Contato
 *
 * A classe Contato representa a entidade de contatos no sistema.
 * Ela é mapeada para a tabela `contatos` no banco de dados e contém informações
 * sobre cada contato, incluindo seu ID, tipo (email ou telefone), descrição
 * e a pessoa associada ao contato.
 *
 * @package App\Entity
 * @author Pierri Alexander Vidmar
 * @since 10/2024
 */
#[ORM\Entity]
#[ORM\Table(name: 'contatos')]
class Contato
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private $id;

    #[ORM\Column(type: 'boolean', nullable: false)]
    private $tipo;

    #[ORM\Column(type: 'string', nullable: true)]
    private $descricao;

    #[ORM\ManyToOne(targetEntity: Pessoa::class, inversedBy: 'contatos')]
    #[ORM\JoinColumn(name: 'idPessoa', referencedColumnName: 'id', nullable: false)]
    private $pessoa;

    /**
     * Obtém o ID do contato.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Obtém o tipo do contato.
     *
     * @return bool
     */
    public function getTipo(): bool
    {
        return $this->tipo;
    }

    /**
     * Define o tipo do contato.
     *
     * @param bool $tipo
     */
    public function setTipo(bool $tipo): void
    {
        $this->tipo = $tipo;
    }

    /**
     * Obtém a descrição do contato.
     *
     * @return string|null
     */
    public function getDescricao(): ?string
    {
        return $this->descricao;
    }

    /**
     * Define a descrição do contato.
     *
     * @param string|null $descricao
     */
    public function setDescricao(?string $descricao): void
    {
        $this->descricao = $descricao;
    }

    /**
     * Obtém a pessoa associada ao contato.
     *
     * @return Pessoa|null
     */
    public function getPessoa(): ?Pessoa
    {
        return $this->pessoa;
    }

    /**
     * Define a pessoa associada ao contato.
     *
     * @param Pessoa|null $pessoa
     */
    public function setPessoa(?Pessoa $pessoa): void
    {
        $this->pessoa = $pessoa;
    }

    /**
     * Verifica se o contato é do tipo email.
     *
     * @return bool
     */
    public function isEmail(): bool
    {
        return $this->tipo === true;
    }

    /**
     * Verifica se o contato é do tipo telefone.
     *
     * @return bool
     */
    public function isPhone(): bool
    {
        return $this->tipo === false;
    }
}
