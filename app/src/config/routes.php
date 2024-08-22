<?php

use FastRoute\RouteCollector;

return function(RouteCollector $route) {
  $route->addGroup('/pierre', function(RouteCollector $r) {
    $r->get('/', 'MainController::index');
  });
};
