<?php
/**
 * Created by PhpStorm.
 * User: wojoinc
 * Date: 11/4/17
 * Time: 2:32 PM
 */

namespace Cylaborate;

use Thruway\Peer\Client;
use React\ZMQ\Context;
const ZMQHOST = "tcp://127.0.0.1:8082";
class Editor extends Client
{
    protected $clients;
    private $subscribedContexts;

    public function onSessionStart($session, $transport)
    {

        $context = new Context($this->getLoop());
        $pull = $context->getSocket(\ZMQ::SOCKET_PULL);
        $pull->bind(ZMQHOST);
        $pull->on('message', [$this, 'onChunkerUpdate']);
    }

    public function __construct($realm, $loop)
    {
        parent::__construct($realm, $loop);
        $this->subscribedContexts = array();

    }

    function onChunkerUpdate($event)
    {
        $eventData = json_decode($event, true);

        if (!array_key_exists('context', $eventData)) {
            error_log("OnChunkerUpdate failed: Missing or malformed JSON: " . $event);
            return;
        } else {
            // If the context has not been subscribed to, there's no reason to send an update event
            //Was causing an issue as we are not currently keeping track of active contexts. Need to add back in later
            /*if (!array_key_exists($eventData['context'], $this->subscribedContexts)) {
                error_log("Unknown Failure: context: " . $eventData['context'] );
                return;
            }*/
        }
        /**
         * Broadcast update to subscribed clients, it is expected that $eventData['layout']
         * is a JSON string containing the layout data from the database
         */
        if (array_key_exists('data', $eventData)) {
            $this->getSession()->publish($eventData['context'], $eventData['data']);
        } else {
            error_log("Failed to update subscribed clients, no layout was given!: " . $event);
            return;
        }

    }
}