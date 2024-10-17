<?php

// src/Controller/ContatoController.php

namespace App\Controller;

use App\Service\ContatoService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class ContatoController
 *
 * O controlador ContatoController é responsável por gerenciar as operações
 * relacionadas aos contatos na aplicação. Ele utiliza o serviço ContatoService
 * para realizar ações como criar, listar, obter, atualizar e deletar contatos.
 *
 * @package App\Controller
 * @author Pierri Alexander Vidmar
 * @since 10/2024
 */
class ContatoController
{
    private ContatoService $contatoService;

    /**
     * ContatoController constructor.
     *
     * @param ContatoService $contatoService O serviço de contato que será utilizado pelo controlador.
     */
    public function __construct(ContatoService $contatoService)
    {
        $this->contatoService = $contatoService;
    }



    // API ==========

    /**
     * Cria um novo contato.
     *
     * @param Request $request A requisição HTTP contendo os dados do contato.
     * @return Response Retorna uma resposta JSON com os dados do contato criado ou um erro.
     */
    public function criar(Request $request): Response
    {
        $dados = json_decode($request->getContent(), true);

        if (!$dados || !isset($dados['descricao']) || !isset($dados['tipo']) || !isset($dados['idPessoa']))
        {
            return new JsonResponse(['erro' => 'Dados inválidos'], Response::HTTP_BAD_REQUEST);
        }

        // Cria um novo contato, passando o ID da pessoa
        $contato = $this->contatoService->criarContato((bool)$dados['tipo'], $dados['descricao'], $dados['idPessoa']);

        return new JsonResponse([
            'id' => $contato->getId(),
            'tipo' => $contato->getTipo() ? 'Email' : 'Telefone',
            'descricao' => $contato->getDescricao(),
            'idPessoa' => $contato->getPessoa()->getId(), // Adicionando o ID da pessoa associada
        ], Response::HTTP_CREATED);
    }

    /**
     * Lista todos os contatos.
     *
     * @return Response Retorna uma resposta JSON com a lista de contatos.
     */
    public function listar(): Response
    {
        $contatos = $this->contatoService->listarContatos();
        $resultado = [];

        foreach ($contatos as $contato)
        {
            $resultado[] = [
                'id' => $contato->getId(),
                'tipo' => $contato->getTipo() ? 'Email' : 'Telefone',
                'descricao' => $contato->getDescricao(),
                'idPessoa' => $contato->getPessoa()->getId(), // ID da pessoa associada
            ];
        }

        return new JsonResponse($resultado, Response::HTTP_OK);
    }

    /**
     * Lista todos os contatos.
     *
     * @return Response Retorna uma resposta JSON com a lista de contatos.
     */
    public function listarComPessoa(): Response
    {
        $contatos = $this->contatoService->listarContatos();
        $resultado = [];

        foreach ($contatos as $contato)
        {
            $resultado[] = [
                'id' => $contato->getId(),
                'tipo' => $contato->getTipo() ? 'Email' : 'Telefone',
                'descricao' => $contato->getDescricao(),
                'pessoa' => [
                    'id' => $contato->getPessoa()->getId(),
                    'nome' => $contato->getPessoa()->getNome(),
                    'cpf' => $contato->getPessoa()->getCpf()
                ],
            ];
        }

        return new JsonResponse($resultado, Response::HTTP_OK);
    }
}
