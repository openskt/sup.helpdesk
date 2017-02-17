<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @name: Ticket Model
 * @author: Somkit T.
 */
class Ticket_model extends CI_Model
{

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Add new ticket
    public function add_new($data, $due_date) {
        // Check urgently field
        // Add new ticket
        $this->db->set('due_date', $due_date, FALSE);
        $this->db->insert("ticket", $data);
        return $this->db->insert_id();
    }

    // update ticket now for
    // email and project_id only
    // will be better on next version
    public function update($ticket_id, $data){
        //$this->db->set('email_enduser', $email);
        //$this->db->set('project_id', $project_id);
        $this->db->set($data);
        $this->db->where('id', $ticket_id);
        return ($this->db->update('ticket')) ? "Successfully update" : "Fail to update";
    }

    // return details of this ticket
    public function details($ticket_id) {
        $this->db->select('a.id as id, a.subject as subject, a.details as details, a.urgent as urgent, a.priority as priority, b.fname as create_by_fname, b.lname as create_by_lname, a.due_date as due_date, c.email as enduser_email, d.tstamp as create_datetime, a.state_level as state_level, a.project_id as project_id');
        $this->db->from('ticket a');
        $this->db->join('user b', 'b.id=a.create_by', 'left');
        $this->db->join('user c', 'c.id=a.end_user', 'left');
        $this->db->join('log_ticket d', 'd.ticket_id=a.id AND d.state_level=1', 'left');
        $this->db->where('ticket_id', $ticket_id);
        $query = $this->db->get();
        return $query->result();
        /*
        //$this->db->select('ticket.*, user.email as enduser_email');
        $this->db->from('ticket');
        //$this->db->join('user', array('user.id' => 'ticket.end_user')'user.id = ticket.end_user', 'ticket');
        //$this->db->join('user', 'user.id = ticket.end_user AND ticket.end_user IS NOT NULL');
        $this->db->where('ticket.id', $ticket_id);
        //$this->db->where('log_ticket.state_level', 1);
        $query = $this->db->get();
        return $query->result();
        */
    }

    // return number of ticket that you have picked
    public function count_picked($user_id) {
        $this->db->where('user_id', $user_id);
        $this->db->where('active', 1);
        $this->db->from('task');
        return $this->db->count_all_results();
    }

    // list tickets all
    public function list_all_active() {
        //$this->db->select('ticket.*, log_ticket.tstamp as ')
        $this->db->from('ticket');
        $this->db->where('is_active', 1);
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        return $query->result();
    }

    // this function be called when staff pick the ticket
    public function pick($ticket_id, $user_id) {

        // check if this guy alreay pick this ticket
        $this->db->from('pick');
        $this->db->where('ticket_id', $ticket_id);
        $this->db->where('user_id', $user_id);

        // if not exist then insert the new one
        if($this->db->count_all_results() < 1)
        {
            $data = array(
                'ticket_id' => $ticket_id,
                'user_id'   => $user_id
            );

            $this->db->insert("pick", $data);
        }
    }

    // return data array of my picked ticket
    public function my_picked($user_id) {
        // join between pick and ticket
        // where pick.active=1
        $this->db->select('*');
        $this->db->from('pick');
        $this->db->where('active', 1);
        $this->db->where('user_id', $user_id);
        $this->db->join('ticket', 'ticket.id = pick.ticket_id');
        $this->db->order_by('pick_timestamp', 'desc');
        $query = $this->db->get();
        return $query->result();
    }

    // return result arayy of people who picked this ticket
    public function picked_by($ticket_id) {
        $this->db->select('task.id as id, task.state_level as task_state, user.fname as fname, user.lname as lname');
        $this->db->from('task');
        $this->db->where('active', 1);
        $this->db->where('ticket_id', $ticket_id);
        $this->db->join('user', 'user.id = task.user_id');
        $query = $this->db->get();
        return $query->result();
    }

    // approve thicket
    public function approve($pick_id, $approv_by) {
        $data = array(
            'approv_by' => $approv_by,
            'approve'   => 1
        );
        // no escape for approv_timestamp
        $this->db->set('approv_timestamp', "NOW()", FALSE);
        $this->db->where('id', $pick_id);
        $this->db->update('pick', $data);
        return TRUE;
    }

    // change state of this ticket
    public function change_state($ticket_id, $state){
        $this->db->set('state', $state);
        $this->db->where('id', $ticket_id);
        return ($this->db->update('ticket')) ? TRUE : FALSE;
    }

    // check state of this ticket
    // return state of the ticket
    public function get_state($ticket_id){
        //return $this->db->get_where('ticket', array('id' => $ticket_id)) -> row();
        return $this->db->select('state_level')
                    ->get_where('ticket', array('id' => $ticket_id))
                    ->row()
                    ->state_level;
    }

    // close connection
    function __destruct() {
        $this->db->close();
    }
}
