<?php
/**
 * Created by PhpStorm.
 * User: wojoinc
 * Date: 10/18/17
 * Time: 3:43 PM
 */

/**
 * Code from Ratchet beginner's tutorial, learning web sockets using the Ratchet framework
 *
 * TODO finish up user class once the user system is working so that mentions and code tags can be added
 * TODO store log in DB and download recent conversation to chat window when user connects so its not just blank
 */

namespace Cylaborate;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface
{
    const WELCOME_MSG = "Welcome to the Cylaborate chat server!";
    protected $clients;
    protected $users;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        $this->users = array();
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // Store the new connection to send new \SplObjectStorage;messages to later
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId}) : ({$conn->remoteAddress})\n";
        //send welcome, announce across server
        $conn->send(self::WELCOME_MSG);
        $this->broadcast($conn, "SERVER: New client connected! ({$conn->resourceId}) : ({$conn->remoteAddress})\n");
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {

        if (!array_key_exists($from->resourceId, $this->users)) {
            $this->setUserInfo($msg, $from->resourceId);
            return;
        }
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d : %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $from->remoteAddress, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        foreach ($this->clients as $client) {
            $client->send($this->users[$from->resourceId]->getName() . ": " . $msg);
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }

    /**
     * Expanding on code from tutorial, this allows the chat server to broadcast messages to everyone that
     * is not the sender, useful for announcing users, etc.
     * @param ConnectionInterface $from
     * @param $msg
     */
    protected function broadcast(ConnectionInterface $from, $msg)
    {
        foreach ($this->clients as $client) {
            if ($from !== $client) {
                // The sender is not the receiver, send to each client connected
                $client->send($msg);
            }
        }
    }

    protected function setUserInfo($msg, $connid)
    {
        $info = json_decode($msg, true);
        if ($info == null) die("Could not set user info! \ninfo: $info\nmsg: " . $msg);
        $this->users[$connid] = new User($info['username'], $connid);
        return true;
    }

}