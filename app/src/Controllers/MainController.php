<?php
namespace Pierre\SimpleFramework\Controllers;

use Middlewares\Utils\Factory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class MainController
{
  public static function(ServerRequestInterface $request): ResponseInterface
  {
    $response = Factory::createResponse(200, 'OK');
    $response->getBody()->write(json_encode(['message' => 'Welcome to SimpleFramework']));
    return $response;
  }
}
