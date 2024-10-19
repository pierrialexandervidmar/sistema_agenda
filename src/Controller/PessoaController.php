<?php

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
     * @param PessoaService $pessoaService
     */
    public function __construct(PessoaService $pessoaService)
    {
        $this->pessoaService = $pessoaService;
    }

    /**
     * Lista todas as pessoas.
     *
     * @param Request $request 
     * @return Response 
     */
    public function index(Request $request): Response
    {
        $pessoas = $this->pessoaService->listarPessoas();

        $resultado = [];

        foreach ($pessoas as $pessoa)
        {
            $resultado[] = [
                'id' => $pessoa->getId(),
                'nome' => $pessoa->getNome(),
                'cpf' => $this->pessoaService->formatarCPF($pessoa->getCpf())
            ];
        }

        $viewPath = __DIR__ . '/../Views/Pessoa/pessoa.php';

        if (file_exists($viewPath))
        {
            extract(['pessoas' => $resultado]);
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
     * Cria uma nova pessoa a partir do formulário da view.
     *
     * @param Request $request
     * @return Response 
     */
    public function criarPessoaView(Request $request): Response
    {
        if ($request->isMethod('POST'))
        {
            $dados = $request->request->all();

            if (!isset($dados['nome']) || !isset($dados['cpf']))
            {
                return new JsonResponse(['erro' => 'Dados inválidos'], Response::HTTP_BAD_REQUEST);
            }

            if (!$this->pessoaService->validarCPF($dados['cpf']))
            {
                return new JsonResponse(['erro' => 'CPF INVÁLIDO'], Response::HTTP_BAD_REQUEST);
            }

            $this->pessoaService->criarPessoa($dados['nome'], $dados['cpf']);

            return new Response('', Response::HTTP_NO_CONTENT);
        }

        return new Response('Método não permitido', Response::HTTP_METHOD_NOT_ALLOWED);
    }

    /**
     * Exclui uma pessoa a partir do ID fornecido.
     *
     * @param Request $request
     * @return Response
     */
    public function excluirPessoaView(Request $request): Response
    {
        if ($request->isMethod('POST'))
        {
            $dados = $request->request->all();

            if (!isset($dados['id']))
            {
                return new JsonResponse(['erro' => 'Dados inválidos'], Response::HTTP_BAD_REQUEST);
            }

            $this->pessoaService->deletarPessoa($dados['id']);

            return new Response('', Response::HTTP_NO_CONTENT);
        }

        return new Response('Método não permitido', Response::HTTP_METHOD_NOT_ALLOWED);
    }

    /**
     * Obtém uma pessoa pelo ID.
     *
     * @param Request $request 
     * @param int $id 
     * @return Response 
     */
    public function obterPessoaView(Request $request, int $id): Response
    {
        try
        {
            $pessoa = $this->pessoaService->obterPessoaPorId($id);

            return new JsonResponse([
                'id' => $pessoa->getId(),
                'nome' => $pessoa->getNome(),
                'cpf' => $this->pessoaService->formatarCPF($pessoa->getCpf())
            ], Response::HTTP_OK);
        }
        catch (\Exception $e)
        {
            return new JsonResponse(['erro' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Atualiza uma pessoa existente.
     *
     * @param Request $request 
     * @return Response 
     */
    public function editarPessoaView(Request $request): Response
    {
        if ($request->isMethod('POST'))
        {
            $dados = $request->request->all();

            if (!isset($dados['id']) || !isset($dados['nome']) || !isset($dados['cpf']))
            {
                return new JsonResponse(['erro' => 'Dados inválidos'], Response::HTTP_BAD_REQUEST);
            }

            if (!$this->pessoaService->validarCPF($dados['cpf']))
            {
                return new JsonResponse(['erro' => 'CPF INVÁLIDO'], Response::HTTP_BAD_REQUEST);
            }

            try
            {
                $this->pessoaService->atualizarPessoa((int)$dados['id'], $dados['nome'], $dados['cpf']);
                return new Response('', Response::HTTP_NO_CONTENT);
            }
            catch (EntityNotFoundException $e)
            {
                return new JsonResponse(['erro' => 'Pessoa não encontrada.'], Response::HTTP_NOT_FOUND);
            }
            catch (\Exception $e)
            {
                return new JsonResponse(['erro' => 'Erro ao atualizar a pessoa.'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        return new Response('Método não permitido', Response::HTTP_METHOD_NOT_ALLOWED);
    }

    // API ==========

    /**
     * Cria uma nova pessoa.
     *
     * @param Request $request 
     * @return Response 
     */
    public function criar(Request $request): Response
    {
        $dados = json_decode($request->getContent(), true);

        if (!$dados || !isset($dados['nome']) || !isset($dados['cpf']))
        {
            return new JsonResponse(['erro' => 'Dados inválidos, verifique se todos os dados estão corretos e enviados'], Response::HTTP_BAD_REQUEST);
        }

        if (!$this->pessoaService->validarCPF($dados['cpf']))
        {
            return new JsonResponse(['erro' => 'CPF INVÁLIDO'], Response::HTTP_BAD_REQUEST);
        }

        $pessoa = $this->pessoaService->criarPessoa($dados['nome'], $dados['cpf']);

        return new JsonResponse([
            'id' => $pessoa->getId(),
            'nome' => $pessoa->getNome(),
            'cpf' => $pessoa->getCpf(),
        ], Response::HTTP_CREATED);
    }

    /**
     * Lista todas as pessoas com seus contatos.
     *
     * @return Response Retorna uma resposta JSON com a lista de pessoas e seus contatos.
     */
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

    /**
     * Obtém uma pessoa pelo ID com seus contatos.
     *
     * @param Request $request 
     * @param int $id 
     * @return Response 
     */
    public function obter(Request $request, int $id): Response
    {
        try
        {
            $pessoaComContatos = $this->pessoaService->obterPessoaComContatosPorId($id);

            return new JsonResponse($pessoaComContatos, Response::HTTP_OK);
        }
        catch (\Exception $e)
        {
            return new JsonResponse(['erro' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }
}
