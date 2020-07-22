<?php

use App\Http\Middleware\ErrorHandler;
use App\Http\Middleware\NotFoundHandler;
use Framework\Http\Application;
use Framework\Http\Pipeline\LaminasPipelineAdapter;
use Framework\Http\Pipeline\MiddlewareResolver;
use Framework\Http\Psr7\LaminasResponseFactory;
use Framework\Http\Psr7\ResponseFactory;
use Framework\Http\Router\AuraRouterAdapter;
use Framework\Http\Router\Router;
use Furious\Container\Container;

/** @var Container $container */

### Framework

$container->set(Router::class, function (Container $container) {
    return $container->get(AuraRouterAdapter::class);
});

$container->set(MiddlewareResolver::class, function (Container $container) {
    return new MiddlewareResolver($container);
});

$container->set(Application::class, function (Container $container) {
    return new Application(
        $container->get(MiddlewareResolver::class),
        $container->get(Router::class),
        $container->get(NotFoundHandler::class),
        $container->get(LaminasPipelineAdapter::class)
    );
});

### App

$container->set(ResponseFactory::class, function (Container $container) {
    return $container->get(LaminasResponseFactory::class);
});

$container->set(ErrorHandler::class, function (Container $container) {
    return new ErrorHandler(
        $container->get(ResponseFactory::class),
        boolval($container->get('config')['debug'])
    );
});