<?php
/**
 * Created by PhpStorm.
 * User: sndo9
 * Date: 11/7/17
 * Time: 4:37 PM
 */

namespace Cylaborate;

class Chunker
{

    private $layout;
    private $contents = Array();

    private $project;
    private $branch;
    private $file;
    private $version;

    private $project_id;
    private $branch_id;
    private $file_id;

    private $connect;

    private $context;

    const ZMQHOST = "tcp://localhost:8082";

    public function __construct($c, $p, $b, $f, $v, $cont)
    {
        $this->project = $p;
        $this->branch = $b;
        $this->file = $f;
        $this->version = $v;

        $this->connect = $c;

        $this->context = $cont;

        $this->get_ids();

    }

    /*
     * return
     *  0 no error
     * -1 file in project and branch does not exist
     */
    public function get_layout()
    {

        if(!\does_file_exist($this->connect, $this->file, $this->project_id, $this->branch_id, $this->version))
        {
            return -1;
        }

        $this->layout = \get_file_layout_from_table($this->connect, $this->file, $this->project_id, $this->branch_id, $this->version);

        $this->contents = json_decode($this->layout);
        error_log($this->layout);

        return 0;
    }

    /*
     * returns:
     *  0 no error
     * -1 position is outside array
     * -2 id does not match position
     */
    public function update_by_position_and_id($position, $id, $contents)
    {
        if($position >= sizeof($this->contents))
        {
            return -1;
        }
        //if(!array_key_exists($this->contents[$position], $id))
        if($this->contents[$position] != $id)
        {
            log_output("log.txt", $this->contents[$position] . "\n");
            log_output("log.txt", $id . "\n");

            return -2;
        }

        $encoded_contents = base64_encode($contents);
        //$encoded_contents = $contents;

        $new_id = hash('sha512', $encoded_contents);

        $this->contents[$position] = $new_id;

        //Add chunk to table

        if(!\does_chunk_exist_by_id($this->connect, $new_id))
        {
            \add_chunk($this->connect, $new_id, $encoded_contents);
        }

        return 0;
    }


    public function add_chunk_to_end($content)
    {
        $encoded_contents = base64_encode($content);
        //$encoded_contents = $content;

        $id = hash('sha512', $encoded_contents);

        array_push($this->contents, $id);

        if(!\does_chunk_exist_by_id($this->connect, $id))
        {
            \add_chunk($this->connect, $id, $encoded_contents);
        }
    }

    /*
     * returns:
     *  0 no error
     * -1 position is outside array
     *
     */
    public function add_chunk_after_id($position, $content)
    {
        if($position > sizeof($this->contents))
        {
            return -1;
        }

        $encoded_contents = base64_encode($content);
        //$encoded_contents = $content;

        $id = hash('sha512', $encoded_contents);

        $new_contents = Array();

        for($i = 0; $i < sizeof($this->contents); $i++)
        {
            if($i == $position)
            {
                array_push($new_contents, $id);
            }
            array_push($new_contents, $this->contents[$i]);
        }

        $this->contents = $new_contents;

        if(!\does_chunk_exist_by_id($this->connect, $id))
        {
            \add_chunk($this->connect, $id, $encoded_contents);
        }

        return 0;
    }

    /*
     * return:
     *  0 no error
     * -1 position is outside array
     * -2 id does not match id at position
     */
    public function delete_chunk_by_id_and_position($position)
    {
        if($position >= sizeof($this->contents))
        {
            return -1;
        }

        $new_contents = Array();

        for($i = 0; $i < sizeof($this->contents); $i++)
        {
            if($i != $position)
            {
                array_push($new_contents, $this->contents[$i]);
            }
        }

        $this->contents = $new_contents;

        return 0;
    }

    public function get_page_contents()
    {
        $outer = Array();

        for($i = 0; $i < sizeof($this->contents); $i++)
        {
            $block = new \stdClass();

            $block->id = $this->contents[$i];

            $block->content = base64_decode(get_chunk_contents($this->connect, $block->id));
            //$block->content = get_chunk_contents($this->connect, $block->id);

            array_push($outer, $block);
        }

        //print_r2($outer);

        return json_encode($outer);
    }

    public function push_new_layout()
    {
        $outer = Array();

        for($i = 0; $i < sizeof($this->contents); $i++)
        {
//            error_log("size " . sizeof($this->contents), 0);
//            error_log($this->contents[$i] . " " . $i, 0);
            $block = new \stdClass();

            $block->id = $this->contents[$i];

            $block->content = base64_decode(get_chunk_contents($this->connect, $block->id));
            //$block->content = get_chunk_contents($this->connect, $block->id);

            array_push($outer, $block);
        }

        $context = new \ZMQContext();
        $socket = $context->getSocket(\ZMQ::SOCKET_PUSH);
        $socket->connect("tcp://localhost:8082");

        //zmq $json_outer

        //context => channel
        //data => content array

        $message = array(
            'context' => $this->context,
            'data' => $outer
        );

        $enc_message = json_encode($message);

        error_log("");

        $socket->send($enc_message);

        //error_log("Message sent.\n");

        error_log($enc_message);

        //print_r2($message);
    }

    public function save_layout_to_table_()
    {
        //update layout_by_project_branch_file
        $this->layout = json_encode($this->contents);

        save_layout_to_table($this->connect, $this->layout, $this->project_id, $this->branch_id, $this->file, $this->version);
    }

    //Testing functions
    public function get_content()
    {
        return $this->contents;
    }

    /*
     * returns:
     *  0
     * -1 project name fails
     * -2 branch id lookup fails
     */
    private function get_ids()
    {
        $project_id = \get_project_id($this->connect, $this->project);

        if($project_id == -1)
        {
            return -1;
        }

        $branch_id = \get_branch_id($this->connect, $project_id, $this->branch);

        if($branch_id == -1)
        {
            return -2;
        }

        $this->project_id = $project_id;
        $this->branch_id = $branch_id;

        return 0;
    }

    public function save_to_disk($timestamp)
    {
        error_log("trying to save", 0);
        $content = json_decode($this->get_page_contents());

        $path_name = "../../projects/". $this->project . "/" . $this->branch . "/" . $timestamp . "/";
        mkdir($path_name, 0777, true);

        file_put_contents($path_name . $this->file, "//version: $this->version" . "\n", FILE_APPEND);

        foreach($content as $chunk)
        {
            //error_log($chunk->content, 0);
            file_put_contents($path_name . $this->file, $chunk->content . "\n", FILE_APPEND);
        }

        $zip = escapeshellarg("../../projects/". $this->project . "/" . $this->branch . "/" . $timestamp . ".zip");
        $cmd = "zip -r $zip " . escapeshellarg($path_name);

        error_log(shell_exec(escapeshellcmd($cmd)), 0);

    }

}