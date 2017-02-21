<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @name: Ticket Model
 * @author: Somkit T.
 */
class Task_model extends CI_Model
{

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // set deny to 1 if
    // approve not equal 1
    // on this ticket_id
    public function deny_pick($ticket_id) {
        $this->db->where('ticket_id', $ticket_id);
        $this->db->where('approve', 0);
        $this->db->update('task', array('deny' => 1));
    }

    // change state_level of task
    public function change_state($task_id, $data){
        $this->db->where('id', $task_id);
        $this->db->update('task', $data);
        //echo "affected_rows=".$this->db->affected_rows();
        //exit();
        if($this->db->affected_rows() == 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    // list all task from state_level and ticket_id
    public function list_all($ticket_id) {
        $this->db->from('task');
        $this->db->where('ticket_id', $ticket_id);
        $query = $this->db->get();
        return $query->result();
    }

    // close connection
    function __destruct() {
        $this->db->close();
    }
}
