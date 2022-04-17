<?php
/**
 * Created by PhpStorm.
 * User: rishabkinnerkar
 * Date: 9/24/2017
 * Time: 5:58 PM
/**
 * Created by PhpStorm.
 * User: rishabkinnerkar
 * Date: 9/24/2017
 * Time: 5:05 PM
 */

namespace Chat_Application;


class Chat implements MessageComponentInterface {
    public function onOpen(ConnectionInterface $conn) {
    }

    public function onMessage(ConnectionInterface $from, $msg) {
    }

    public function onClose(ConnectionInterface $conn) {
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
    }
}