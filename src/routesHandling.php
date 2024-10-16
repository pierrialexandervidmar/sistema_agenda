<?php
/**
 * Este arquivo gerencia o roteamento para as rotas de API e Web, processando 
 * as requisições HTTP e invocando o controlador apropriado com base nas rotas e métodos.
 * Rotas de API com prefixo /api/.
 *
 * @author Pierri Alexander Vidmar
 * @since 10/2024
 */

use Symfony\Component\HttpFoundation\Response;

function handleApiRouting($routesAPI, $container, $request)
{
    processRoutes($routesAPI, $container, $request);
}

function handleWebRouting($routesWeb, $container, $request)
{
    processRoutes($routesWeb, $container, $request);
}

function processRoutes($routes, $container, $request)
{
    $path = rtrim($request->getPathInfo(), '/');
    $method = $request->getMethod();

    foreach ($routes as $route) {
        $routePath = $route['path'];
        $routeMethods = $route['methods'];

        if (strpos($routePath, '/api/') === 0 && strpos($path, '/api/') !== 0) {
            continue;
        }

        if (strpos($routePath, '/api/') === 0 && strpos($path, '/api/') === 0) {
            if (in_array($method, $routeMethods)) {
                if (preg_match('~^' . preg_replace('/{[a-zA-Z0-9_]+}/', '([a-zA-Z0-9_]+)', $routePath) . '$~', $path, $matches)) {
                    array_shift($matches);

                    if (strpos($route['controller'], '::') !== false) {
                        list($controllerClass, $method) = explode('::', $route['controller']);
                        $routeService = $route['service'] ?? null;
                        $service = $routeService ? $container->get($routeService) : null;
                        $controllerInstance = new $controllerClass($service);
                        $response = call_user_func_array(
                            [$controllerInstance, $method],
                            array_merge([$request], array_slice($matches, 0))
                        );
                    } else {
                        $response = new Response('Controller not found', Response::HTTP_INTERNAL_SERVER_ERROR);
                    }

                    $response->send();
                    exit;
                }
            }
        } elseif (strpos($routePath, '/api/') !== 0) {
            if (in_array($method, $routeMethods)) {
                if (preg_match('~^' . preg_replace('/{[a-zA-Z0-9_]+}/', '([a-zA-Z0-9_]+)', $routePath) . '$~', $path, $matches)) {
                    array_shift($matches);

                    if (strpos($route['controller'], '::') !== false) {
                        list($controllerClass, $method) = explode('::', $route['controller']);
                        $routeService = $route['service'] ?? null;
                        $service = $routeService ? $container->get($routeService) : null;
                        $controllerInstance = new $controllerClass($service);
                        $response = call_user_func_array(
                            [$controllerInstance, $method],
                            array_merge([$request], array_slice($matches, 0))
                        );
                    } else {
                        $response = new Response('Controller not found', Response::HTTP_INTERNAL_SERVER_ERROR);
                    }

                    $response->send();
                    exit;
                }
            }
        }
    }

    $response = new Response('Not Found', Response::HTTP_NOT_FOUND);
    $response->send();
}
