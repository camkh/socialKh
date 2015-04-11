<?php



if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class Home extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mod_general');
        $this->load->theme('layout');

    }


    public function index()
    {
        $this->load->theme('layout');
        $data['title'] = 'Welcome to my site';
        $this->load->view('home', $data);

    }
    
    public function managecampaigns()
    {
        $this->load->theme('layout');
        $data['title'] = 'Admin Area :: Manage Campaigns';
        $this->load->view('managecampaigns/list', $data);

    }

    public function logout()
    {
        $this->session->sess_destroy();
        //HybridAuth
        $this->load->library('HybridAuthLib');
        $this->hybridauthlib->logoutAllProviders();
        redirect(base_url());
    }


}


/* End of file welcome.php */

/* Location: ./application/controllers/welcome.php */
