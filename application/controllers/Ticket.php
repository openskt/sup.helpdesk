<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @name Ticket.php
 * @author Somkit Thap-arsa
 */
class Ticket extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        // Load ticket model
        $this->load->model("Ticket_model", "ticket");

        if(empty($this->session->userdata('id')))
        {
            $this->session->set_flashdata('flash_data', 'You don\'t have access!');
            redirect('login');
        }
    }

    public function index()
    {
        $data['count_my_pticket'] = $this->ticket->count_picked($this->session->userdata('id'));
        // data passing to view
        $data['page_title'] = "OPENTicket 1.0 | Ticket Approval";
        $data['active_menu'] = "ticket";

        // request data to model
        $data['records'] = $this->ticket->list_all_active();

        // load the view
        $this->load->view('head', $data);
        $this->load->view('aside', $data);
        $this->load->view('body_ticket', $data);
        $this->load->view('footer');
    }

    // List picked ticket
    public function my_picked()
    {
        // setup basic page property
        $data['page_title'] = "OPENTicket 1.0 | Ticket Listing";
        $data['active_menu'] = "ticket";

        $data['count_my_pticket'] = $this->ticket->count_picked($this->session->userdata('id'));

        // load data from model
        $data['records'] = $this->ticket->my_picked($this->session->userdata('id'));

        // load the view
        $this->load->view('head', $data);
        $this->load->view('aside', $data);
        $this->load->view('body_my_picked', $data);
        $this->load->view('footer');
    }

    // Show detail of the ticket
    public function details()
    {
        // load data from model
        // count no of picked ticket
        $data['count_my_pticket'] = $this->ticket->count_picked($this->session->userdata('id'));

        $data['page_title'] = "OPENTicket 1.0 | Ticket Listing";
        $data['active_menu'] = "ticket";

        // ticket_id
        $ticket_id = $this->uri->segment('3');

        // query to get details of this ticket
        $data['ticket'] = $this->ticket->details($ticket_id);

        // load project model
        $this->load->model("Project_model", "project");
        // load logging model
        $this->load->model("Logging_model", "logging");

        $data['ticket_create_datetime'] = $this->logging->get_ticket_create_datetime($ticket_id);

        // get list of project curently active
        // next update shold specific department or section
        // not list all
        $data['projects'] = $this->project->list_all_active();

        // query who(es) picked this ticket
        // also return status of this ticket as approved?
        $data['picked_by'] = $this->ticket->picked_by($ticket_id);

        // load the view
        $this->load->view('head-ext-1', $data);
        $this->load->view('aside', $data);
        $this->load->view('body_ticket_details', $data);
        $this->load->view('footer-ext-1');
    }

    // Book the ticket when staff "Pick Ticket"
    public function pick()
    {
        // check for duplicate records
        // that mean this staff already pick once
        $this->ticket->pick($this->input->post('ticket_id'), $this->session->userdata('id'));

        // go back home
        // should go to "ticket/my_picked"
        redirect('/ticket', 'refresh');
    }

    // Function Add New Ticket
    // Create by SKT
    public function add_new()
    {
        // use save in model
        $this->ticket->add_new($_POST);
        // use helpers loadded via autoload.config
        redirect('/ticket', 'refresh');

    }

    // approve ticket
    public function approve()
    {

        $pick_id = $_POST['pick_id'];
        $ticket_id = $_POST['ticket_id'];

        if($this->ticket->approve($pick_id, $this->session->userdata('id'))){
            // successfully approve this task
            // change ticket state to 'assign'
            echo ($this->ticket->change_state($ticket_id, 'assign')) ? "Success" : "Failed";
        }else{
            echo "Failed";
        }
    }

    // update ticket
    public function update(){

        $ticket_id = $this->input->post('ticket_id');
        // check state of ticket
        $t_state = $this->ticket->get_state($ticket_id);
        //$t_state = "new";
        //echo 'state='.$t_state;

        if($t_state < 4) {
            $subject    = $_POST['subject'];
            $details    = $_POST['details'];
            $project_id = $_POST['project_id'];
            $email      = $_POST['email'];
            $ticket_id  = $_POST['ticket_id'];
            $urgent     = $_POST['urgent'];
            $priority   = $_POST['priority'];
            $duedate    = $_POST['duedate'];

            // js did it
            //if($urgent != 1) $urgent = 0;

            //echo var_dump($_POST);
            //exit();

            // handle the email
            // load email helper
            $this->load->helper('email');
            if(!valid_email($email)) {
                echo "Error: Bad e-mail address.";
            } else {
                // if the email is ok then
                // 1. check in `user` table for this email if exist
                // end_user = user.id
                // 2. if not add new user add get inserted_id here
            }
            if($subject == "" || $details == "" || $duedate == ""){
                echo "Error: Please complete all required fields.";
            }else{
                $data = array(
                    'subject'       => $subject,
                    'details'       => $details,
                    'end_user'      => $email,
                    'project_id'    => $project_id,
                    'urgent'        => $urgent,
                    'due_date'       => $duedate,
                    'priority'      => $priority
                );
                echo $this->ticket->update($ticket_id, $data);
            }
        } else {
            echo "Error: This ticket can not modify because of its STATE.";
        }

    }

    // function kick_off
    // by Somkit T.

    // kick off the ticket
    // - do not allow to modify the ticket
    // - do not allow to pick this ticket
    // - change state of ticket to 'START'
    // - staff who picked this ticket can click on 'Start' to start working on his/her task
    // - deny pick that not approved

    // -----------------------------------------
    // system
    // -----------------------------------------
    // log who, when do this
    public function kick_off(){
        // load aditional model
        $this->load->model("Task_model", "task");

        $ticket_id = $this->input->post('ticket_id');

        // check state of ticket first
        if($this->ticket->get_state($ticket_id)=='assign') {
            // chnage state of the ticket
            $this->ticket->change_state($ticket_id, 'start');
            $this->task->deny_pick($ticket_id);
            echo "Success: Let do it!";
        } else {
            echo "Error: This ticket can not modify because of its STATE.";
        }

    }

}
