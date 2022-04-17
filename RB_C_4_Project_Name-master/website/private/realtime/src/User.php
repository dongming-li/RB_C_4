<?php
/**
 * Created by PhpStorm.
 * User: 97wes
 * Date: 10/19/2017
 * Time: 4:08 PM
 */

namespace Cylaborate;


class User
{
    protected $_conn;
    private $_username;

    public function __construct($name, $conn)
    {
        $this->_conn = $conn;
        $this->_username = $name;
    }
    public function getName(){
        return $this->_username;
    }
}