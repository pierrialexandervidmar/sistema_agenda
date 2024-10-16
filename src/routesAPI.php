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
    ]
];
