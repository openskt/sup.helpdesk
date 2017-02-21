<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @name: Logging Model
 * @author: Somkit T.
 */
class Logging_model extends CI_Model
{

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // return datetime  of the ticket when its created
    public function get_ticket_create_datetime($ticket_id){
        $this->db->select('tstamp');
        $query = $this->db->get_where('log_ticket', array('ticket_id' => $ticket_id, 'state_level' => 1));
        return $query->result();
        /*
      return $this->db->select('tstamp')
              ->get_where('log_ticket', array('ticket_id' => $ticket_id, 'state_level' => 1))
              ->row()
              ->tstamp;
              */
    }

        // Logging for ticket
        public function ticket($data) {
            $this->db->insert("log_ticket", $data);
        }

        public function task($data) {
            return $this->db->insert("log_task", $data);
        }

    // list all currently active project
    /*
    public function list_all_active() {
        $this->db->from('project');
        $this->db->where('active', 1);
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        return $query->result();
    }

    // change state of this ticket
    public function change_state($ticket_id, $state){
        $this->db->set('state', $state);
        $this->db->where('id', $ticket_id);
        return ($this->db->update('ticket')) ? TRUE : FALSE;
    }
*/
    // close connection
    function __destruct() {
        $this->db->close();
    }
}
