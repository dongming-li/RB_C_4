<?php
/**
 * Created by PhpStorm.
 * User: rishabkinnerkar
 * Date: 9/24/2017
 * Time: 5:07 PM
 */
use Ratchet\Server\IoServer;
use \Chat_Application\Chat;

    require dirname(__DIR__) . '/vendor/autoload.php';

    $server = IoServer::factory(
        new Chat(),
        8080
    );

    $server->run();