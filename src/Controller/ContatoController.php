<?php

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
     * @param ContatoService $contatoService
     */
    public function __construct(ContatoService $contatoService)
    {
        $this->contatoService = $contatoService;
    }

    /**
     * Renderiza a visão de contatos.
     *
     * @param Request $request 
     * @return Response 
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
            extract(['contatos' => $resultado, 'pessoas' => $pessoas]);
            ob_start();
            include $viewPath;
            $content = ob_get_clean();
        }
        else
        {
            $content = "Página não encontrada.";
        }

        return new Response($content);
    }

    /**
     * Obtém a visão de um contato específico pelo ID.
     *
     * @param Request $request 
     * @param int $id 
     * @return Response 
     */
    public function obterContatoView(Request $request, int $id): Response
    {
        try
        {
            $contato = $this->contatoService->obterContatoPorId($id);
            $pessoa = $contato->getPessoa();

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

            return new JsonResponse($data, Response::HTTP_OK);
        }
        catch (\Exception $e)
        {
            return new JsonResponse(['erro' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Cria um novo contato a partir do formulário da view.
     *
     * @param Request $request 
     * @return Response
     */
    public function criarContatoView(Request $request): Response
    {
        if ($request->isMethod('POST'))
        {
            $dados = $request->request->all();

            if (!isset($dados['descricao']) || !isset($dados['tipo']) || !isset($dados['idPessoa']))
            {
                return new JsonResponse(['erro' => 'Dados inválidos'], Response::HTTP_BAD_REQUEST);
            }

            $this->contatoService->criarContato((bool)$dados['tipo'], $dados['descricao'], $dados['idPessoa']);

            return new Response('', Response::HTTP_NO_CONTENT);
        }

        return new Response('Método não permitido', Response::HTTP_METHOD_NOT_ALLOWED);
    }

    /**
     * Exclui um contato a partir do formulário da view.
     *
     * @param Request $request 
     * @return Response 
     */
    public function excluirContatoView(Request $request): Response
    {
        if ($request->isMethod('POST'))
        {
            $dados = $request->request->all();

            if (!isset($dados['id']))
            {
                return new JsonResponse(['erro' => 'Dados inválidos'], Response::HTTP_BAD_REQUEST);
            }

            $this->contatoService->deletarContato($dados['id']);

            return new Response('', Response::HTTP_NO_CONTENT);
        }

        return new Response('Método não permitido', Response::HTTP_METHOD_NOT_ALLOWED);
    }

    /**
     * Edita um contato a partir do formulário da view.
     *
     * @param Request $request 
     * @return Response 
     */
    public function editarContatoView(Request $request): Response
    {
        if ($request->isMethod('POST'))
        {
            $dados = $request->request->all();

            if (!isset($dados['id']) || !isset($dados['descricao']) || !isset($dados['tipo']) || !isset($dados['idPessoa']))
            {
                return new JsonResponse(['erro' => 'Dados inválidos'], Response::HTTP_BAD_REQUEST);
            }

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

        return new Response('Método não permitido', Response::HTTP_METHOD_NOT_ALLOWED);
    }

    // API ==========

    /**
     * Cria um novo contato.
     *
     * @param Request $request 
     * @return Response 
     */
    public function criar(Request $request): Response
    {
        $dados = json_decode($request->getContent(), true);

        if (!$dados || !isset($dados['descricao']) || !isset($dados['tipo']) || !isset($dados['idPessoa']))
        {
            return new JsonResponse(['erro' => 'Dados inválidos'], Response::HTTP_BAD_REQUEST);
        }

        $contato = $this->contatoService->criarContato((bool)$dados['tipo'], $dados['descricao'], $dados['idPessoa']);

        return new JsonResponse([
            'id' => $contato->getId(),
            'tipo' => $contato->getTipo() ? 'Email' : 'Telefone',
            'descricao' => $contato->getDescricao(),
            'idPessoa' => $contato->getPessoa()->getId(),
        ], Response::HTTP_CREATED);
    }

    /**
     * Lista todos os contatos.
     *
     * @return Response 
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
                'idPessoa' => $contato->getPessoa()->getId(),
            ];
        }

        return new JsonResponse($resultado);
    }

    /**
     * Lista todos os contatos.
     *
     * @return Response 
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
     * Obtém um contato específico pelo ID.
     *
     * @param int $id 
     * @return Response
     */
    public function obter(int $id): Response
    {
        try
        {
            $contato = $this->contatoService->obterContatoPorId($id);
            $pessoa = $contato->getPessoa();

            $data = [
                'id' => $contato->getId(),
                'tipo' => $contato->getTipo() ? 'Email' : 'Telefone',
                'descricao' => $contato->getDescricao(),
                'idPessoa' => $pessoa->getId(),
            ];

            return new JsonResponse($data, Response::HTTP_OK);
        }
        catch (\Exception $e)
        {
            return new JsonResponse(['erro' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Obtém um contato pelo ID.
     *
     * @param int $id 
     * @return Response 
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
     * @param Request $request 
     * @param int $id 
     * @return Response 
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
            $this->contatoService->atualizarContato($id, (bool)$dados['tipo'], $dados['descricao'], (int)$dados['idPessoa']);
            return new JsonResponse(['id' => $id], Response::HTTP_OK);
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

    /**
     * Deleta um contato existente.
     *
     * @param int $id 
     * @return Response 
     */
    public function deletar(int $id): Response
    {
        try
        {
            $this->contatoService->deletarContato($id);
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }
        catch (\Exception $e)
        {
            return new JsonResponse(['erro' => 'Erro ao deletar o contato.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
