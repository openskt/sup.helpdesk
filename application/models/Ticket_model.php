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
    public function add_new($data) {
        // Check urgently field
        if(isset($data['urgent']) && ($data['urgent'] == 'on')){
            $urgent = 1;
        }else{
            $urgent = 0;
        }

        // Check attached
        // Upload it into DB

        // Check Due due_date
        switch($data['due']) {
          case "3h":
            $due_date = 'NOW() + INTERVAL 3 HOUR';
            break;
          case "6h":
            $due_date = 'NOW() + INTERVAL 6 HOUR';
            break;
          case "24h":
            $due_date = 'NOW() + INTERVAL 1 DAY';
            break;
          case "3d":
            $due_date = 'NOW() + INTERVAL 3 DAY';
            break;
          case "7d":
            $due_date = 'NOW() + INTERVAL 7 DAY';
            break;
        }

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
        //$this->db->select('ticket.*, log_ticket.tstamp as create_datetime');
        $this->db->from('ticket');
        //$this->db->join('log_ticket', 'log_ticket.ticket_id = ticket.id');
        $this->db->where('ticket.id', $ticket_id);
        //$this->db->where('log_ticket.state_level', 1);
        $query = $this->db->get();
        return $query->result();
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
