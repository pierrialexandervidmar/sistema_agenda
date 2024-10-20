<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Classe Pessoa
 *
 * Representa uma entidade de Pessoa, que possui um nome, um CPF e uma coleção de contatos associados.
 *
 * @package App\Entity
 * @author Pierri Alexander Vidmar
 * @since 10/2024
 */
#[ORM\Entity]
#[ORM\Table(name: 'pessoas')]
class Pessoa
{
    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private $id;

    /**
     * @var string O nome da pessoa.
     * @ORM\Column(type="string", nullable=false)
     */
    #[ORM\Column(type: 'string', nullable: false)]
    private $nome;

    /**
     * @var string|null O CPF da pessoa.
     * @ORM\Column(type="string", nullable=true)
     */
    #[ORM\Column(type: 'string', nullable: true)]
    private $cpf;

    /**
     * @var Collection|Contato[] A coleção de contatos associados à pessoa.
     * @ORM\OneToMany(mappedBy="pessoa", targetEntity=Contato::class, cascade={"persist", "remove"})
     */
    #[ORM\OneToMany(mappedBy: 'pessoa', targetEntity: Contato::class, cascade: ['persist', 'remove'])]
    private $contatos;

    /**
     * Pessoa constructor.
     * Inicializa a coleção de contatos.
     */
    public function __construct()
    {
        $this->contatos = new ArrayCollection();
    }

    /**
     * Obtém o identificador da pessoa.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Obtém o nome da pessoa.
     *
     * @return string
     */
    public function getNome(): string
    {
        return $this->nome;
    }

    /**
     * Define o nome da pessoa.
     *
     * @param string $nome
     */
    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    /**
     * Obtém o CPF da pessoa.
     *
     * @return string|null
     */
    public function getCpf(): ?string
    {
        return $this->cpf;
    }

    /**
     * Define o CPF da pessoa.
     *
     * @param string $cpf
     */
    public function setCpf(string $cpf): void
    {
        $this->cpf = $cpf;
    }

    /**
     * Obtém a coleção de contatos associados à pessoa.
     *
     * @return Collection|Contato[]
     */
    public function getContatos(): Collection
    {
        return $this->contatos;
    }

    /**
     * Adiciona um contato à coleção de contatos da pessoa.
     *
     * @param Contato $contato
     */
    public function addContato(Contato $contato): void
    {
        if (!$this->contatos->contains($contato))
        {
            $this->contatos[] = $contato;
            $contato->setPessoa($this);
        }
    }

    /**
     * Remove um contato da coleção de contatos da pessoa.
     *
     * @param Contato $contato
     */
    public function removeContato(Contato $contato): void
    {
        if ($this->contatos->removeElement($contato))
        {
            if ($contato->getPessoa() === $this)
            {
                $contato->setPessoa(null);
            }
        }
    }
}
