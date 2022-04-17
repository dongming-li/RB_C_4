<?php
/**
 * Created by PhpStorm.
 * User: sndo9
 * Date: 11/7/17
 * Time: 4:38 PM
 */

namespace Cylaborate;

class Tuple
{

    private $id;
    private $content;

    public function __construct($new_id, $new_content)
    {
        $this->id = $new_id;
        $this->content = $new_content;
    }

    public function set_id($new_id)
    {
        $this->id = $new_id;
    }

    public function set_content($new_content)
    {
        $this->content = $new_content;
    }

    public function get_id()
    {
        return $this->id;
    }

    public function get_content()
    {
        return $this->content;
    }

    public function print_both()
    {
        echo $this->id . ": " . $this->content . "<br>";
    }

}