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
        $this->db->update('pick', array('deny' => 1));
    }

    // close connection
    function __destruct() {
        $this->db->close();
    }
}
