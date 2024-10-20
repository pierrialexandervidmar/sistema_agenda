<?php

namespace App\Service;

use App\Entity\Pessoa;
use App\Repository\PessoaRepository;
use App\Repository\ContatoRepository;
use Doctrine\ORM\EntityNotFoundException;

/**
 * Classe PessoaService
 *
 * Responsável pela lógica de negócios relacionada às pessoas.
 * Interage com o PessoaRepository e ContatoRepository para operações CRUD e manipulação de contatos.
 *
 * @package App\Service
 * @author Pierri Alexander Vidmar
 * @since 10/2024
 */ class PessoaService
{
    private PessoaRepository $pessoaRepository;
    private ContatoRepository $contatoRepository;

    /**
     * Método construtor.
     *
     * @param PessoaRepository $pessoaRepository 
     * @param ContatoRepository $contatoRepository
     */
    public function __construct(PessoaRepository $pessoaRepository, ContatoRepository $contatoRepository)
    {
        $this->pessoaRepository = $pessoaRepository;
        $this->contatoRepository = $contatoRepository;
    }

    /**
     * Cria uma nova pessoa.
     *
     * @param string $nome 
     * @param string $cpf 
     * @return Pessoa
     */
    public function criarPessoa(string $nome, string $cpf): Pessoa
    {
        $pessoa = new Pessoa();
        $pessoa->setNome($nome);
        $pessoa->setCpf($cpf);

        $this->pessoaRepository->salvar($pessoa);

        return $pessoa;
    }

    /**
     * Lista todas as pessoas.
     *
     * @return Pessoa[]
     */
    public function listarPessoas(): array
    {
        return $this->pessoaRepository->buscarTodos();
    }

    /**
     * Obtém uma pessoa pelo ID, incluindo seus contatos.
     *
     * @param int $id 
     * @return array 
     * @throws EntityNotFoundException
     */
    public function obterPessoaComContatosPorId(int $id): array
    {
        $pessoa = $this->pessoaRepository->buscarPorId($id);

        if (!$pessoa)
        {
            throw new EntityNotFoundException('Pessoa não encontrada.');
        }

        $contatos = $this->contatoRepository->buscarContatoPessoa(['pessoa' => $pessoa->getId()]);

        $contatosMapeados = array_map(function ($contato)
        {
            return [
                'tipo' => $contato->getTipo() ? "Email" : "Telefone",
                'descricao' => $contato->getDescricao(),
            ];
        }, $contatos);

        return [
            'id' => $pessoa->getId(),
            'nome' => $pessoa->getNome(),
            'cpf' => $pessoa->getCpf(),
            'contatos' => $contatosMapeados,
        ];
    }

    /**
     * Obtém uma pessoa pelo ID.
     *
     * @param int $id 
     * @return Pessoa 
     * @throws EntityNotFoundException 
     */
    public function obterPessoaPorId(int $id): Pessoa
    {
        $pessoa = $this->pessoaRepository->buscarPorId($id);

        if (!$pessoa)
        {
            throw new EntityNotFoundException('Pessoa não encontrada.');
        }

        return $pessoa;
    }

    /**
     * Lista todas as pessoas com seus contatos.
     *
     * @return array
     */
    public function listarPessoasComContatos(): array
    {
        $pessoas = $this->pessoaRepository->buscarTodos();
        $resultado = [];

        foreach ($pessoas as $pessoa)
        {
            $contatos = $this->contatoRepository->buscarContatoPessoa(['pessoa' => $pessoa->getId()]);
            $resultado[] = [
                'id' => $pessoa->getId(),
                'nome' => $pessoa->getNome(),
                'cpf' => $pessoa->getCpf(),
                'contatos' => $contatos,
            ];
        }

        return $resultado;
    }

    /**
     * Atualiza uma pessoa existente.
     *
     * @param int $id
     * @param string $nome
     * @param string $cpf 
     * @return Pessoa
     * @throws EntityNotFoundException 
     */
    public function atualizarPessoa(int $id, string $nome, string $cpf): Pessoa
    {
        $pessoa = $this->obterPessoaPorId($id);
        $pessoa->setNome($nome);
        $pessoa->setCpf($cpf);

        $this->pessoaRepository->atualizar($pessoa);

        return $pessoa;
    }

    /**
     * Deleta uma pessoa pelo ID.
     *
     * @param int $id
     * @throws EntityNotFoundException 
     */
    public function deletarPessoa(int $id): void
    {
        $pessoa = $this->obterPessoaPorId($id);
        $this->pessoaRepository->deletar($pessoa);
    }

    /**
     * Busca uma pessoa pelo CPF.
     *
     * @param string $cpf 
     * @return Pessoa|null
     */
    public function buscarPessoaPorCpf(string $cpf): ?Pessoa
    {
        return $this->pessoaRepository->buscarPessoaCpf($cpf);
    }

    /**
     * Busca pessoas pelo nome.
     *
     * @param string $nome
     * @return Pessoa[]
     */
    public function buscarPessoasPorNome(string $nome): array
    {
        return $this->pessoaRepository->buscarPessoaNome($nome);
    }

    /**
     * Busca pessoas de acordo com critérios específicos enviados no array de parâmetro.
     *
     * @param array $criterios Critérios de busca.
     * @return Pessoa[] Retorna um array de pessoas que correspondem aos critérios.
     */
    public function buscarPorCriterio(array $criterios): array
    {
        return $this->pessoaRepository->buscarPorCriterio($criterios);
    }

    /**
     * Valida o CPF fornecido.
     *
     * @param string $cpf 
     * @return bool 
     */
    public function validarCPF(string $cpf): bool
    {
        // Remove qualquer máscara de formatação (pontos e traços)
        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        // Verifica se o CPF tem 11 dígitos
        if (strlen($cpf) != 11)
        {
            return false;
        }

        // Verifica se todos os dígitos são iguais
        if (preg_match('/(\d)\1{10}/', $cpf))
        {
            return false;
        }

        // Cálculo do primeiro dígito verificador
        for ($t = 9; $t < 11; $t++)
        {
            $d = 0;
            for ($c = 0; $c < $t; $c++)
            {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d)
            {
                return false;
            }
        }

        return true;
    }

    /**
     * Formata o CPF no padrão 'XXX.XXX.XXX-XX'.
     *
     * @param string $cpf 
     * @return string
     */
    public function formatarCPF(string $cpf): string
    {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        if (strlen($cpf) == 11)
        {
            return substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9, 2);
        }

        return $cpf;
    }
}
