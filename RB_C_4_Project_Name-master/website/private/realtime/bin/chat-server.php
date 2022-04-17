<?php
/**
 * Created by PhpStorm.
 * User: wojoinc
 * Date: 10/18/17
 * Time: 3:44 PM
 */

/**
 * Code from Ratchet beginner's tutorial. Learning Ratchet framework.
 */

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Cylaborate\Chat;

require dirname(__DIR__) . '/vendor/autoload.php';

$chatServer = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Chat()
        )
    ),
    8080
);
$chatServer->run();
