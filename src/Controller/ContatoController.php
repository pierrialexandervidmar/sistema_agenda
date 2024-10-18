<?php

// src/Controller/ContatoController.php

namespace App\Controller;

use App\Service\ContatoService;
use Doctrine\ORM\EntityNotFoundException;
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
 * **Uso:**
 * Este controlador deve ser usado para manipular requisições HTTP relacionadas
 * aos contatos. Ele processa as entradas, chama os serviços correspondentes e
 * retorna as respostas apropriadas.
 *
 * **Exemplo:**
 * Para criar um novo contato:
 * ```php
 * $contatoController = new ContatoController($contatoService);
 * $response = $contatoController->criar($request);
 * ```
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

    /**
     * Renderiza a visão de contatos.
     *
     * @param Request $request A requisição HTTP.
     * @return Response Retorna uma resposta com a visão de contatos.
     */
    public function index(Request $request): Response
    {
        $contatos = $this->contatoService->listarContatos();
        $pessoas = $this->contatoService->buscarPessoas();

        $resultado = [];

        foreach ($contatos as $contato)
        {
            $resultado[] = [
                'id' => $contato->getId(),
                'tipo' => $contato->getTipo(),
                'descricao' => $contato->getDescricao(),
                'pessoa' => [
                    'id' => $contato->getPessoa()->getId(),
                    'nome' => $contato->getPessoa()->getNome(),
                    'cpf' => $contato->getPessoa()->getCpf()
                ],
            ];
        }

        $viewPath = __DIR__ . '/../Views/Contato/contato.php';

        if (file_exists($viewPath))
        {
            // Extraindo a variável $resultado para a view
            extract(['contatos' => $resultado, 'pessoas' => $pessoas]);
            ob_start();
            include $viewPath; // Passa a lista de contatos para a view
            $content = ob_get_clean();
        }
        else
        {
            $content = "Página não encontrada.";
        }

        return new Response($content);
    }

    public function obterContatoView(Request $request, int $id): Response
    {
        try
        {
            // Obtém o contato pelo ID
            $contato = $this->contatoService->obterContatoPorId($id);
            $pessoa = $contato->getPessoa();

            // Prepara os dados do contato para retornar como JSON
            $data = [
                'id' => $contato->getId(),
                'tipo' => $contato->getTipo() ? 'Email' : 'Telefone',
                'descricao' => $contato->getDescricao(),
                'pessoa' => [
                    'id' => $pessoa->getId(),
                    'nome' => $pessoa->getNome(),
                    'cpf' => $pessoa->getCpf()
                ],
            ];

            // Retorna os dados do contato em formato JSON
            return new JsonResponse($data, Response::HTTP_OK);
        }
        catch (\Exception $e)
        {
            // Retorna um erro caso ocorra uma exceção
            return new JsonResponse(['erro' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }



    /**
     * Método para criar um novo contato a partir do formulário da view.
     *
     * @param Request $request A requisição HTTP.
     * @return Response Retorna uma resposta redirecionando para a página de contatos após a criação.
     */
    public function criarContatoView(Request $request): Response
    {
        if ($request->isMethod('POST'))
        {
            $dados = $request->request->all(); // Obtém os dados do formulário

            if (!isset($dados['descricao']) || !isset($dados['tipo']) || !isset($dados['idPessoa']))
            {
                return new JsonResponse(['erro' => 'Dados inválidos'], Response::HTTP_BAD_REQUEST);
            }

            $this->contatoService->criarContato((bool)$dados['tipo'], $dados['descricao'], $dados['idPessoa']);

            return new Response('', Response::HTTP_NO_CONTENT);
        }

        // Se não for um método POST, redireciona para a página de contatos
        return new Response('Método não permitido', Response::HTTP_METHOD_NOT_ALLOWED);
    }

    /**
     * Método para criar um novo contato a partir do formulário da view.
     *
     * @param Request $request A requisição HTTP.
     * @return Response Retorna uma resposta redirecionando para a página de contatos após a criação.
     */
    public function excluirContatoView(Request $request): Response
    {
        if ($request->isMethod('POST'))
        {
            $dados = $request->request->all(); // Obtém os dados do formulário

            if (!isset($dados['id']))
            {
                return new JsonResponse(['erro' => 'Dados inválidos'], Response::HTTP_BAD_REQUEST);
            }

            $this->contatoService->deletarContato($dados['id']);

            return new Response('', Response::HTTP_NO_CONTENT);
        }

        // Se não for um método POST, redireciona para a página de contatos
        return new Response('Método não permitido', Response::HTTP_METHOD_NOT_ALLOWED);
    }

    public function editarContatoView(Request $request): Response
    {
        if ($request->isMethod('POST'))
        {
            $dados = $request->request->all(); // Obtém os dados do formulário

            // Valida se os dados necessários estão presentes
            if (!isset($dados['id']) || !isset($dados['descricao']) || !isset($dados['tipo']) || !isset($dados['idPessoa']))
            {
                return new JsonResponse(['erro' => 'Dados inválidos'], Response::HTTP_BAD_REQUEST);
            }

            // Tenta atualizar o contato
            try
            {
                $this->contatoService->atualizarContato((int)$dados['id'], (bool)$dados['tipo'], $dados['descricao'], (int)$dados['idPessoa']);
                return new Response('', Response::HTTP_NO_CONTENT);
            }
            catch (EntityNotFoundException $e)
            {
                return new JsonResponse(['erro' => 'Contato ou pessoa associada não encontrado.'], Response::HTTP_NOT_FOUND);
            }
            catch (\Exception $e)
            {
                return new JsonResponse(['erro' => 'Erro ao atualizar o contato.'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        // Se não for um método POST, redireciona para a página de contatos
        return new Response('Método não permitido', Response::HTTP_METHOD_NOT_ALLOWED);
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

    /**
     * Obtém um contato pelo ID.
     *
     * @param int $id O ID do contato a ser obtido.
     * @return Response Retorna uma resposta JSON com os dados do contato ou um erro.
     */
    public function obter(Request $request, int $id): Response
    {
        try
        {
            $contato = $this->contatoService->obterContatoPorId($id);

            return new JsonResponse([
                'id' => $contato->getId(),
                'tipo' => $contato->getTipo() ? 'Email' : 'Telefone',
                'descricao' => $contato->getDescricao(),
                'idPessoa' => $contato->getPessoa()->getId(), // ID da pessoa associada
            ], Response::HTTP_OK);
        }
        catch (\Exception $e)
        {
            return new JsonResponse(['erro' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Obtém um contato pelo ID.
     *
     * @param int $id O ID do contato a ser obtido.
     * @return Response Retorna uma resposta JSON com os dados do contato ou um erro.
     */
    public function obterComPessoa(Request $request, int $id): Response
    {
        try
        {
            $contato = $this->contatoService->obterContatoPorId($id);
            $pessoa = $contato->getPessoa();

            return new JsonResponse([
                'id' => $contato->getId(),
                'tipo' => $contato->getTipo() ? 'Email' : 'Telefone',
                'descricao' => $contato->getDescricao(),
                'pessoa' => [
                    'id' => $pessoa->getId(),
                    'nome' => $pessoa->getNome(),
                    'cpf' => $pessoa->getCpf()
                ]
            ], Response::HTTP_OK);
        }
        catch (\Exception $e)
        {
            return new JsonResponse(['erro' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }


    /**
     * Atualiza um contato existente.
     *
     * @param Request $request A requisição HTTP contendo os dados atualizados do contato.
     * @param int $id O ID do contato a ser atualizado.
     * @return Response Retorna uma resposta JSON com os dados do contato atualizado ou um erro.
     */
    public function atualizar(Request $request, int $id): Response
    {
        $dados = json_decode($request->getContent(), true);

        if (!$dados || !isset($dados['descricao']) || !isset($dados['tipo']) || !isset($dados['idPessoa']))
        {
            return new JsonResponse(['erro' => 'Dados inválidos'], Response::HTTP_BAD_REQUEST);
        }

        try
        {
            $contato = $this->contatoService->atualizarContato($id, $dados['tipo'], $dados['descricao'], $dados['idPessoa']);

            return new JsonResponse([
                'id' => $contato->getId(),
                'tipo' => $contato->getTipo() ? 'Email' : 'Telefone',
                'descricao' => $contato->getDescricao(),
                'idPessoa' => $contato->getPessoa()->getId(), // ID da pessoa associada
            ], Response::HTTP_OK);
        }
        catch (\Exception $e)
        {
            return new JsonResponse(['erro' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Deleta um contato pelo ID.
     *
     * @param int $id O ID do contato a ser deletado.
     * @return Response Retorna uma resposta JSON confirmando a deleção ou um erro.
     */
    public function deletar(Request $request, int $id): Response
    {
        try
        {
            $this->contatoService->deletarContato($id);
            return new JsonResponse(['mensagem' => 'Contato deletado com sucesso'], Response::HTTP_NO_CONTENT);
        }
        catch (\Exception $e)
        {
            return new JsonResponse(['erro' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }
}
