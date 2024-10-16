<?php
header("Access-Control-Allow-Origin: *"); // Permite qualquer origem
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); // Métodos permitidos
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Cabeçalhos permitidos

require_once 'bootstrap.php';
require_once 'routesHandling.php';

use App\Container;
use Symfony\Component\HttpFoundation\Request;

$container = new Container($entityManager);

$request = Request::createFromGlobals();

$routesAPI = require 'routesAPI.php';
$routesWeb = require 'routesWeb.php';


if (strpos($request->getPathInfo(), '/api/') === 0) {
  handleApiRouting($routesAPI, $container, $request);
} else {
  handleWebRouting($routesWeb, $container, $request);
}
