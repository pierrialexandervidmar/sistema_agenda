<?php

// src/Controller/PessoaController.php

namespace App\Controller;

use App\Service\PessoaService;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class PessoaController
 *
 * O controlador PessoaController é responsável por gerenciar as operações
 * relacionadas às pessoas na aplicação. Ele utiliza o serviço PessoaService
 * para realizar ações como criar, listar, obter, atualizar e deletar pessoas.
 *
 * **Uso:**
 * Este controlador deve ser usado para manipular requisições HTTP relacionadas
 * às pessoas. Ele processa as entradas, chama os serviços correspondentes e
 * retorna as respostas apropriadas.
 *
 * **Exemplo:**
 * Para criar uma nova pessoa:
 * ```php
 * $pessoaController = new PessoaController($pessoaService);
 * $response = $pessoaController->criar($request);
 * ```
 *
 * @package App\Controller
 * @author Pierri Alexander Vidmar
 * @since 10/2024
 */
class PessoaController
{
    private PessoaService $pessoaService;

    /**
     * PessoaController constructor.
     *
     * @param PessoaService $pessoaService O serviço de pessoa que será utilizado pelo controlador.
     */
    public function __construct(PessoaService $pessoaService)
    {
        $this->pessoaService = $pessoaService;
    }



    // API ==========

    /**
     * Cria uma nova pessoa.
     *
     * @param Request $request A requisição HTTP contendo os dados da pessoa.
     * @return Response Retorna uma resposta JSON com os dados da pessoa criada ou um erro.
     */
    public function criar(Request $request): Response
    {
        $dados = json_decode($request->getContent(), true);

        if (!$dados || !isset($dados['nome']) || !isset($dados['cpf']))
        {
            return new JsonResponse(['erro' => 'Dados inválidos'], Response::HTTP_BAD_REQUEST);
        }

        $pessoa = $this->pessoaService->criarPessoa($dados['nome'], $dados['cpf']);

        return new JsonResponse([
            'id' => $pessoa->getId(),
            'nome' => $pessoa->getNome(),
            'cpf' => $pessoa->getCpf(),
        ], Response::HTTP_CREATED);
    }
    public function listar(): Response
    {
        $pessoasComContatos = $this->pessoaService->listarPessoasComContatos();

        $resultado = [];

        foreach ($pessoasComContatos as $pessoaComContato)
        {
            $resultado[] = [
                'id' => $pessoaComContato['id'],
                'nome' => $pessoaComContato['nome'],
                'cpf' => $pessoaComContato['cpf'],
                'contatos' => array_map(function ($contato)
                {
                    return [
                        'tipo' => $contato->getTipo() ? "Email" : "Telefone",
                        'descricao' => $contato->getDescricao(),
                    ];
                }, $pessoaComContato['contatos']),
            ];
        }

        return new JsonResponse($resultado, Response::HTTP_OK);
    }

}
