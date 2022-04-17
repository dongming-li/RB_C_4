<?php
/**
 * Created by PhpStorm.
 * User: 97wes
 * Date: 11/5/2017
 * Time: 11:39 PM
 */

use Cylaborate\Editor;
use Thruway\Peer\Router;
use Thruway\Transport\RatchetTransportProvider;
require dirname(__DIR__) . '/vendor/autoload.php';

$router = new Router();
$realm = "realm1";

$router->addInternalClient(new Editor($realm, $router->getLoop()));
$router->addTransportProvider(new RatchetTransportProvider("0.0.0.0", 8081));
$router->start();