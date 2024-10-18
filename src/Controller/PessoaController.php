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

    public function index(Request $request)
    {
        $pessoas = $this->pessoaService->listarPessoas();

        $resultado = [];

        foreach ($pessoas as $pessoa)
        {
            $resultado[] = [
                'id' => $pessoa->getId(),      // Usar métodos getter
                'nome' => $pessoa->getNome(),
                'cpf' => $pessoa->getCpf()
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
     * Método para criar uma nova pessoa a partir do formulário da view.
     *
     * @param Request $request A requisição HTTP.
     * @return Response Retorna uma resposta redirecionando para a página de pessoas após a criação.
     */
    public function criarPessoaView(Request $request): Response
    {
        if ($request->isMethod('POST'))
        {
            $dados = $request->request->all(); // Obtém os dados do formulário

            if (!isset($dados['nome']) || !isset($dados['cpf']))
            {
                // Em caso de dados inválidos, você pode redirecionar com uma mensagem ou retornar um erro
                return new JsonResponse(['erro' => 'Dados inválidos'], Response::HTTP_BAD_REQUEST);
            }

            // Cria uma nova pessoa
            $this->pessoaService->criarPessoa($dados['nome'], $dados['cpf']);

            // Redireciona de volta para a lista de pessoas após a criação
            return new Response('', Response::HTTP_NO_CONTENT);
        }

        // Se não for um método POST, redireciona para a página de pessoas
        return new Response('Método não permitido', Response::HTTP_METHOD_NOT_ALLOWED);
    }


    public function excluirPessoaView(Request $request): Response
    {
        if ($request->isMethod('POST'))
        {
            $dados = $request->request->all(); // Obtém os dados do formulário

            if (!isset($dados['id']))
            {
                return new JsonResponse(['erro' => 'Dados inválidos'], Response::HTTP_BAD_REQUEST);
            }

            $this->pessoaService->deletarPessoa($dados['id']);

            return new Response('', Response::HTTP_NO_CONTENT);
        }

        // Se não for um método POST, redireciona para a página de contatos
        return new Response('Método não permitido', Response::HTTP_METHOD_NOT_ALLOWED);
    }


    public function obterPessoaView(Request $request, int $id): Response
    {
        try
        {
            // Obtém o contato pelo ID
            $pessoa = $this->pessoaService->obterPessoaPorId($id);

            // Prepara os dados do contato para retornar como JSON
            $data = [
                'id' => $pessoa->getId(),
                'nome' => $pessoa->getNome(),
                'cpf' => $pessoa->getCpf()
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


    public function editarPessoaView(Request $request): Response
    {
        if ($request->isMethod('POST'))
        {
            $dados = $request->request->all(); // Obtém os dados do formulário

            // Valida se os dados necessários estão presentes
            if (!isset($dados['id']) || !isset($dados['nome']) || !isset($dados['cpf']))
            {
                return new JsonResponse(['erro' => 'Dados inválidos'], Response::HTTP_BAD_REQUEST);
            }

            // Tenta atualizar o contato
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
                return new JsonResponse(['erro' => 'Erro ao atualizar a pesoa.'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        // Se não for um método POST, redireciona para a página de contatos
        return new Response('Método não permitido', Response::HTTP_METHOD_NOT_ALLOWED);
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


    /**
     * Obtém uma pessoa pelo ID com seus contatos.
     *
     * @param int $id O ID da pessoa a ser obtida.
     * @return Response Retorna uma resposta JSON com os dados da pessoa ou um erro.
     */
    public function obterComContato(Request $request, int $id): Response
    {
        try
        {
            $pessoa = $this->pessoaService->obterPessoaPorIdComContatos($id);

            return new JsonResponse($pessoa, Response::HTTP_OK);
        }
        catch (\Exception $e)
        {
            return new JsonResponse(['erro' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Obtém uma pessoa pelo ID.
     *
     * @param int $id O ID da pessoa a ser obtida.
     * @return Response Retorna uma resposta JSON com os dados da pessoa ou um erro.
     */
    public function obter(Request $request, int $id): Response
    {
        try
        {
            $pessoa = $this->pessoaService->obterPessoaPorId($id);

            return new JsonResponse([
                'id' => $pessoa->getId(),
                'nome' => $pessoa->getNome(),
                'cpf' => $pessoa->getCpf(),
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
     * @param Request $request A requisição HTTP contendo os dados atualizados da pessoa.
     * @param int $id O ID da pessoa a ser atualizada.
     * @return Response Retorna uma resposta JSON com os dados da pessoa atualizada ou um erro.
     */
    public function atualizar(Request $request, int $id): Response
    {
        $dados = json_decode($request->getContent(), true);

        if (!$dados || !isset($dados['nome']) || !isset($dados['cpf']))
        {
            return new JsonResponse(['erro' => 'Dados inválidos'], Response::HTTP_BAD_REQUEST);
        }

        try
        {
            $pessoa = $this->pessoaService->atualizarPessoa($id, $dados['nome'], $dados['cpf']);

            return new JsonResponse([
                'id' => $pessoa->getId(),
                'nome' => $pessoa->getNome(),
                'cpf' => $pessoa->getCpf(),
            ], Response::HTTP_OK);
        }
        catch (\Exception $e)
        {
            return new JsonResponse(['erro' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Deleta uma pessoa pelo ID.
     *
     * @param int $id O ID da pessoa a ser deletada.
     * @return Response Retorna uma resposta JSON confirmando a deleção ou um erro.
     */
    public function deletar(Request $request, int $id): Response
    {
        try
        {
            $this->pessoaService->deletarPessoa($id);
            return new JsonResponse(['mensagem' => 'Pessoa deletada com sucesso'], Response::HTTP_NO_CONTENT);
        }
        catch (EntityNotFoundException $e)
        {
            return new JsonResponse(['erro' => 'Pessoa não encontrada.'], Response::HTTP_NOT_FOUND);
        }
        catch (\Error $e)
        {
            return new JsonResponse(['erro' => 'Ocorreu um erro interno no servidor.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        catch (\Exception $e)
        {
            return new JsonResponse(['erro' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
