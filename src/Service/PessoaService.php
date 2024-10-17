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
 * @author
 * @since 10/2024
 */
class PessoaService
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
     * @param string $nome O nome da pessoa.
     * @param string $cpf O CPF da pessoa.
     * @return Pessoa Retorna a instância da pessoa criada.
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
     * @return Pessoa[] Retorna um array com todas as pessoas cadastradas.
     */
    public function listarPessoas(): array
    {
        return $this->pessoaRepository->buscarTodos();
    }

    /**
     * Obtém uma pessoa pelo ID, incluindo seus contatos.
     *
     * @param int $id O ID da pessoa a ser obtida.
     * @return array Retorna um array com os dados da pessoa e seus contatos.
     * @throws EntityNotFoundException Se a pessoa não for encontrada.
     */
    public function obterPessoaPorIdComContatos(int $id): array
    {
        $pessoa = $this->pessoaRepository->buscarPorId($id);

        if (!$pessoa) {
            throw new EntityNotFoundException('Pessoa não encontrada.');
        }

        $contatos = $this->contatoRepository->buscarContatoPessoa(['pessoa' => $pessoa->getId()]);

        $contatosMapeados = array_map(function ($contato) {
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
     * Obtém uma pessoa pelo ID
     *
     * @param int $id O ID da pessoa a ser obtida.
     * @return Pessoa
     * @throws EntityNotFoundException Se a pessoa não for encontrada.
     */
    public function obterPessoaPorId(int $id): Pessoa
    {
        $pessoa = $this->pessoaRepository->buscarPorId($id);

        if (!$pessoa) {
            throw new EntityNotFoundException('Pessoa não encontrada.');
        }

        return $pessoa;
    }

    /**
     * Lista todas as pessoas com seus contatos.
     *
     * @return array Retorna um array de pessoas com seus respectivos contatos.
     */
    public function listarPessoasComContatos(): array
    {
        $pessoas = $this->pessoaRepository->buscarTodos();
        $resultado = [];

        foreach ($pessoas as $pessoa) {
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
     * @param int $id O ID da pessoa a ser atualizada.
     * @param string $nome O novo nome da pessoa.
     * @param string $cpf O novo CPF da pessoa.
     * @return Pessoa Retorna a pessoa atualizada.
     * @throws EntityNotFoundException Se a pessoa não for encontrada.
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
     * @param int $id O ID da pessoa a ser deletada.
     * @throws EntityNotFoundException Se a pessoa não for encontrada.
     */
    public function deletarPessoa(int $id): void
    {
        $pessoa = $this->obterPessoaPorId($id);
        $this->pessoaRepository->deletar($pessoa);
    }

    /**
     * Busca uma pessoa pelo CPF.
     *
     * @param string $cpf O CPF da pessoa a ser buscada.
     * @return Pessoa|null Retorna a pessoa encontrada ou null se não for encontrada.
     */
    public function buscarPessoaPorCpf(string $cpf): ?Pessoa
    {
        return $this->pessoaRepository->buscarPessoaCpf($cpf);
    }

    /**
     * Busca pessoas pelo nome.
     *
     * @param string $nome O nome das pessoas a serem buscadas.
     * @return Pessoa[] Retorna um array de pessoas com o nome correspondente.
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
}
