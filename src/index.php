<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  exit(0);
}

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
