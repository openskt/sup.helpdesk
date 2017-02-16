<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @name: Project Model
 * @author: Somkit T.
 */
class Project_model extends CI_Model
{

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Add new ticket
    public function add_new($data) {
        // Check urgently field
        /*

        // Save them all into DB
        $data = array(
            'create_by'   => $this->session->userdata('id'),
            'subject'     => $data['ticket_subject'],
            'details'     => $data['ticket_details'],
            //'due_date'    => $due_date,
            'urgent'    => $urgent
        );

        // ci automatically escape all for security reason
        // so ... have to force to
        // do not escape this var
        $this->db->set('due_date', $due_date, FALSE);
        return $this->db->insert("ticket", $data);
        */
    }

    // return details of this ticket
    public function details($ticket_id) {
        $this->db->from('ticket');
        $this->db->where('id', $ticket_id);
        $query = $this->db->get();
        return $query->result();
    }

    // return number of ticket that you have picked
    public function count_active($dept_id) {
        /*
        $this->db->where('user_id', $user_id);
        $this->db->where('active', 1);
        $this->db->from('pick');
        return $this->db->count_all_results();
        */
    }

    // list all currently active project
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

    // close connection
    function __destruct() {
        $this->db->close();
    }
}
