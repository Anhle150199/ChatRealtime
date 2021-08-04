<?php
use App\Controller\WebsocketController;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

    require dirname(__DIR__) . '/vendor/autoload.php';

    $server = IoServer::factory(
        new HttpServer(
            new WsServer(
                new WebsocketController()
            )
        ),
        8889 
    );

    $server->run();
