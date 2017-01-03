<?php

require __DIR__ . '/../vendor/autoload.php';

use Equip\Route;

Equip\Application::build()
->setConfiguration([
    Equip\Configuration\AurynConfiguration::class,
    Equip\Configuration\DiactorosConfiguration::class,
    Equip\Configuration\PayloadConfiguration::class,
    Equip\Configuration\RelayConfiguration::class,
    Equip\Configuration\SessionConfiguration::class,
    OAuth2\Infrastructure\Configuration\OAuth2ConfigurationSet::class,
])
->setMiddleware([
    Relay\Middleware\ResponseSender::class,
    OAuth2\Application\Middleware\ExceptionMiddleware::class,
    Equip\Handler\DispatchHandler::class,
    Equip\Handler\FormContentHandler::class,
    Equip\Handler\ActionHandler::class,
])
->setRouting(static function (Equip\Directory $directory) {
    return $directory
        ->get('/', OAuth2\Application\Action\Welcome::class)
        ->get('/connect/{provider}', OAuth2\Application\Action\Connect::class)
        ->get('/logout/{provider}', OAuth2\Application\Action\Logout::class)
        ->get('/login/{provider}', new Route(
            OAuth2\Application\Action\Login::class,
            OAuth2\Application\Input\LoginProviderInput::class
        ))
        ->get('/user/{provider}', new Route(
            OAuth2\Application\Action\User::class,
            OAuth2\Application\Input\UserProviderInput::class
        ))
        ->any('/', OAuth2\Application\Action\Welcome::class)
        ; // End of routing
})
->run();
