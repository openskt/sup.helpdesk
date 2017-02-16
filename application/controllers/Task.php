<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @name Ticket.php
 * @author Somkit Thap-arsa
 */
class Task extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        // Load ticket model
        $this->load->model("Task_model", "task");


        if(empty($this->session->userdata('id')))
        {
            $this->session->set_flashdata('flash_data', 'You don\'t have access!');
            redirect('login');
        }
    }

    public function index()
    {

    }


}
