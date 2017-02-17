<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @name: User Model
 * @author: Somkit T.
 */
class User_model extends CI_Model
{

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Check existing of the user given email
    public function is_exist($data){
        $this->db->where($data);
        $query = $this->db->get('user');
        if($query->num_rows() > 0){
            return true;
        } else {
            return false;
        }
    }

    // get u_id from email
    public function get_uid($data) {
        return $this->db->select('id')
                ->get_where('user', $data)
                ->row()
                ->id;
    }

    // Add new user by email
    public function add_new($data) {
        // Check urgently field
        //$this->db->set('due_date', $due_date, FALSE);
        $this->db->insert("user", $data);
        return $this->db->insert_id();
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
