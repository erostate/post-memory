<?php

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Matcher\UrlMatcher;

$request = Request::createFromGlobals();
$response = new Response();

$routes = new RouteCollection();

// Add routes to home: / and /index
$routes->add('home', new Route('/'));
$routes->add('index', new Route('/index'));
$routes->add('product', new Route('/product/{productId}'));
$routes->add('cart', new Route('/cart'));
$routes->add('login', new Route('/login'));
$routes->add('register', new Route('/register'));
$routes->add('login2', new Route('/login2'));

$context = new RequestContext();
$context->fromRequest($request);

$urlMatcher = new UrlMatcher($routes, $context);

$pathInfo = $request->getPathInfo();

try {
    $result = $urlMatcher->match($pathInfo);
    extract($result);
    extract($request->query->all());

    ob_start();
    require __DIR__ . '/src/' . $_route . '.php';
} catch (ResourceNotFoundException $e) {
    require __DIR__ . '/src/404.php';
} catch (MethodNotAllowedException $e) {
    require __DIR__ . '/src/404.php';
}

$response->send();
