<?php

namespace App\Service;

use App\Entity\Contato;
use App\Entity\Pessoa;
use App\Repository\ContatoRepository;
use Doctrine\ORM\EntityNotFoundException;

/**
 * Classe ContatoService
 *
 * ContatoService é responsável pela lógica de negócios relacionada aos contatos.
 * Ele interage com o ContatoRepository para realizar operações de criação, leitura, atualização e exclusão (CRUD).
 *
 * @package App\Service
 * @author Pierri Alexander Vidmar
 * @since 10/2024
 */
class ContatoService
{
    private ContatoRepository $contatoRepository;

    /**
     * Método construtor
     * 
     * Inicializa o ContatoService com uma instância de ContatoRepository.
     *
     * @param ContatoRepository $contatoRepository
     */
    public function __construct(ContatoRepository $contatoRepository)
    {
        $this->contatoRepository = $contatoRepository;
    }

    /**
     * Cria um novo contato.
     *
     * Este método cria um novo contato no sistema, associando-o a uma pessoa existente.
     * Caso a pessoa associada não seja encontrada, uma exceção será lançada.
     *
     * @param bool $tipo 
     * @param string $descricao
     * @param int $idPessoa 
     * @return Contato 
     * @throws EntityNotFoundException 
     */
    public function criarContato(bool $tipo, string $descricao, int $idPessoa): Contato
    {
        $pessoa = $this->contatoRepository->buscarPessoaPorId($idPessoa);

        if (!$pessoa)
        {
            throw new EntityNotFoundException('Pessoa associada não encontrada.');
        }

        $contato = new Contato();
        $contato->setTipo($tipo);
        $contato->setDescricao($descricao);
        $contato->setPessoa($pessoa); // Associar a pessoa ao contato

        $this->contatoRepository->salvar($contato);

        return $contato;
    }

    /**
     * Lista todos os contatos.
     *
     * Recupera todos os contatos armazenados no banco de dados.
     *
     * @return Contato[] 
     */
    public function listarContatos(): array
    {
        return $this->contatoRepository->buscarTodos();
    }

    /**
     * Obtém um contato pelo ID.
     *
     * Busca um contato específico pelo seu ID. Caso o contato não seja encontrado,
     * uma exceção será lançada.
     *
     * @param int $id 
     * @return Contato 
     * @throws EntityNotFoundException 
     */
    public function obterContatoPorId(int $id): Contato
    {
        $contato = $this->contatoRepository->buscarPorId($id);

        if (!$contato)
        {
            throw new EntityNotFoundException('Contato não encontrado.');
        }

        return $contato;
    }

    /**
     * Busca todas as pessoas cadastradas.
     *
     * Este método busca todas as pessoas registradas no sistema para que possam ser associadas a um contato.
     *
     * @return Pessoa[] 
     */
    public function buscarPessoas(): array|Pessoa|null
    {
        return $this->contatoRepository->buscarPessoas();
    }

    /**
     * Atualiza um contato existente.
     *
     * Atualiza os dados de um contato já existente, incluindo seu tipo, descrição e
     * a pessoa associada. Se o contato ou a pessoa não forem encontrados, uma exceção será lançada.
     *
     * @param int $id
     * @param bool $tipo 
     * @param string $descricao 
     * @param int $idPessoa
     * @return Contato 
     * @throws EntityNotFoundException 
     */
    public function atualizarContato(int $id, bool $tipo, string $descricao, int $idPessoa): Contato
    {
        $contato = $this->obterContatoPorId($id);
        $pessoa = $this->contatoRepository->buscarPessoaPorId($idPessoa);

        if (!$pessoa)
        {
            throw new EntityNotFoundException('Pessoa associada não encontrada.');
        }

        $contato->setTipo($tipo);
        $contato->setDescricao($descricao);
        $contato->setPessoa($pessoa); // Atualizar a associação com a pessoa

        $this->contatoRepository->salvar($contato);

        return $contato;
    }

    /**
     * Deleta um contato pelo ID.
     *
     * Remove um contato do banco de dados com base em seu ID. Caso o contato não
     * seja encontrado, uma exceção será lançada.
     *
     * @param int $id 
     * @throws EntityNotFoundException 
     */
    public function deletarContato(int $id): void
    {
        $contato = $this->obterContatoPorId($id);
        $this->contatoRepository->deletar($contato);
    }
}
