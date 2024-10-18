<?php

return [
  // ROTAS HOME
  [
    'path' => '',
    'methods' => ['GET'],
    'controller' => 'App\Controller\HomeController::index',
    'service' => null,
  ],


  // ROTAS DE PESSOAS
  [
    'path' => '/pessoas',
    'methods' => ['GET'],
    'controller' => 'App\Controller\PessoaController::index',
    'service' => 'PessoaService',
  ],
  [
    'path' => '/pessoas/{id}',
    'methods' => ['GET'],
    'controller' => 'App\Controller\PessoaController::obterPessoaView',
    'service' => 'PessoaService',
  ],
  [
    'path' => '/pessoas',
    'methods' => ['POST'],
    'controller' => 'App\Controller\PessoaController::criarPessoaView',
    'service' => 'PessoaService',
  ],
  [
    'path' => '/pessoas/excluir',
    'methods' => ['POST'],
    'controller' => 'App\Controller\PessoaController::excluirPessoaView',
    'service' => 'PessoaService',
  ],
  [
    'path' => '/pessoas/editar',
    'methods' => ['POST'],
    'controller' => 'App\Controller\PessoaController::editarPessoaView',
    'service' => 'PessoaService',
  ],


  // ROTAS DE CONTATO
  [
    'path' => '/contatos',
    'methods' => ['GET'],
    'controller' => 'App\Controller\ContatoController::index',
    'service' => 'ContatoService',
  ],
  [
    'path' => '/contatos/{id}',
    'methods' => ['GET'],
    'controller' => 'App\Controller\ContatoController::obterContatoView',
    'service' => 'ContatoService',
  ],
  [
    'path' => '/contatos',
    'methods' => ['POST'],
    'controller' => 'App\Controller\ContatoController::criarContatoView',
    'service' => 'ContatoService',
  ],
  [
    'path' => '/contatos/excluir',
    'methods' => ['POST'],
    'controller' => 'App\Controller\ContatoController::excluirContatoView',
    'service' => 'ContatoService',
  ],
  [
    'path' => '/contatos/editar',
    'methods' => ['POST'],
    'controller' => 'App\Controller\ContatoController::editarContatoView',
    'service' => 'ContatoService',
  ]
];
