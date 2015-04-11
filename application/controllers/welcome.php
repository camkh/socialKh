<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Welcome extends CI_Controller {

    public function __construct() {        
        parent::__construct();
        $this->load->model('Mod_general');
        $this->load->theme('layout');
    }

    public function index() {
        $this->load->theme('layout');
        $data['title'] = 'User login';
        $data['css'] = array(
            'themes/layout/blueone/assets/css/login',
            'themes/layout/blueone/assets/css/responsive',
            'themes/layout/blueone/assets/css/plugins',
            'themes/layout/blueone/assets/css/icons',
            'themes/layout/blueone/bootstrap/css/bootstrap.min',
            'themes/layout/blueone/assets/css/main',
        );
        $data['addJsScript'] = array(
            "if('ontouchend' in document) document.write('<script src='assets/js/jquery.mobile.custom.min.js'>'+'<'+'/script>');
",
            "function show_box(id) {
			 jQuery('.widget-box.visible').removeClass('visible');
			 jQuery('#'+id).addClass('visible');
			}
"
        );
        $data['bodyClass'] = 'login';        
        /* form */

        $this->load->library('form_validation');
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        if ($this->form_validation->run() == FALSE) {
            
        } else {
            if ($this->input->post('username')) {
                $user = $this->input->post('username');
                $password = $this->input->post('password');
                $field = array(
                    'username',
                    'log_id',
                    'user_type'
                );
                $where = array('username' => $user, 'password' => md5($password));
                $query = $this->Mod_general->getuser($field, $where);
                if (count($query) > 0) {
                    foreach ($query as $row) {
                        $this->session->set_userdata('password', $password);
                        $this->session->set_userdata('username', $row->username);
                        $this->session->set_userdata('user_type', $row->user_type);
                        $this->session->set_userdata('log_id', $row->log_id);
                        redirect(base_url() . 'home/index');
                    }
                }
            }
        }
        $user = $this->session->userdata('email');
        if ($user) {
            redirect(base_url() . 'home');
        } else {
            //$this->load->view('login', $data);
            redirect(base_url() . 'hauth');
        }
    }

    public function login() {
        $this->load->theme('layout');
        $data['title'] = 'User login';
        $data['css'] = array(
            'themes/layout/blueone/assets/css/login',
            'themes/layout/blueone/assets/css/responsive',
        );
        $data['addJsScript'] = array(
            "if('ontouchend' in document) document.write('<script src='assets/js/jquery.mobile.custom.min.js'>'+'<'+'/script>');
",
            "function show_box(id) {
			 jQuery('.widget-box.visible').removeClass('visible');
			 jQuery('#'+id).addClass('visible');
			}
"
        );
        $data['bodyClass'] = 'login';

        /* form */

        $this->load->library('form_validation');
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        if ($this->form_validation->run() == FALSE) {
            
        } else {
            if ($this->input->post('username')) {
                $user = $this->input->post('username');
                $password = $this->input->post('password');
                $field = array(
                    'username',
                    'log_id',
                    'user_type'
                );
                $where = array(
                    'username' => $user, 
                    'password' => md5($password),
                    'user_status' => 1,
                    );
                $query = $this->Mod_general->getuser($field, $where);
                if (count($query) > 0) {
                    foreach ($query as $row) {
                        $this->session->set_userdata('username', $row->username);
                        $this->session->set_userdata('user_type', $row->user_type);
                        $this->session->set_userdata('log_id', $row->log_id);
                        redirect(base_url() . 'home');
                    }
                }
            }
        }
        $user = $this->session->userdata('username');
        if ($user) {
            redirect(base_url() . 'home');
        } else {
            $this->load->view('login', $data);
        }
    }
    
    public function adderrorlog(){
        $song = (!empty($_GET['song']) ? $_GET['song'] : '');
        if(!empty($song)) {
           $data_blog_update_1 = array(Tbl_songmeta::value => $song, Tbl_songmeta::type=>'error_song');
            $datamusic = $this->Mod_general->select(Tbl_songmeta::tblname, '*', $data_blog_update_1); 
            if(empty($datamusic)) {
                $data_post_id = array(
                    Tbl_songmeta::type => 'error_song',
                    Tbl_songmeta::value => $song,
                    );
                $dataPostID = $this->Mod_general->insert(Tbl_songmeta::tblname, $data_post_id);
            }
        }
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */