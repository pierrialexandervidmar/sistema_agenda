<?php

return [
    // ROTAS DE CONTATOS
    'contact_create' => [
        'path' => '/api/contatos',
        'controller' => 'App\Controller\ContatoController::criar',
        'service' => 'ContatoService',
        'methods' => ['POST'],
    ],
    'contact_list' => [
        'path' => '/api/contatos',
        'controller' => 'App\Controller\ContatoController::listar',
        'service' => 'ContatoService',
        'methods' => ['GET'],
    ],
    'contact_list_person' => [
        'path' => '/api/contatos/pessoas',
        'controller' => 'App\Controller\ContatoController::listarComPessoa',
        'service' => 'ContatoService',
        'methods' => ['GET'],
    ],
    'contact_get' => [
        'path' => '/api/contatos/{id}',
        'controller' => 'App\Controller\ContatoController::obter',
        'service' => 'ContatoService',
        'methods' => ['GET'],
    ],
    'contact_get_person' => [
        'path' => '/api/contatos/pessoas/{id}',
        'controller' => 'App\Controller\ContatoController::obterComPessoa',
        'service' => 'ContatoService',
        'methods' => ['GET'],
    ],
    'contact_update' => [
        'path' => '/api/contatos/{id}',
        'controller' => 'App\Controller\ContatoController::atualizar',
        'service' => 'ContatoService',
        'methods' => ['PUT'],
    ],
    'contact_delete' => [
        'path' => '/api/contatos/{id}',
        'controller' => 'App\Controller\ContatoController::deletar',
        'service' => 'ContatoService',
        'methods' => ['DELETE'],
    ],


    
    // ROTAS DE PESSOA
    'pessoa_create' => [
        'path' => '/api/pessoas',
        'controller' => 'App\Controller\PessoaController::criar',
        'service' => 'PessoaService',
        'methods' => ['POST'],
    ],
    'pessoa_list' => [
        'path' => '/api/pessoas',
        'controller' => 'App\Controller\PessoaController::listar',
        'service' => 'PessoaService',
        'methods' => ['GET'],
    ],
    'pessoa_get' => [
        'path' => '/api/pessoas/{id}',
        'controller' => 'App\Controller\PessoaController::obter',
        'service' => 'PessoaService',
        'methods' => ['GET'],
    ],
    'pessoa_get_contatos' => [
        'path' => '/api/pessoas/contatos/{id}',
        'controller' => 'App\Controller\PessoaController::obterComContato',
        'service' => 'PessoaService',
        'methods' => ['GET'],
    ],
    'pessoa_put' => [
        'path' => '/api/pessoas/{id}',
        'controller' => 'App\Controller\PessoaController::atualizar',
        'service' => 'PessoaService',
        'methods' => ['PUT'],
    ],
    'pessoa_delete' => [
        'path' => '/api/pessoas/{id}',
        'controller' => 'App\Controller\PessoaController::deletar',
        'service' => 'PessoaService',
        'methods' => ['DELETE'],
    ],
];
