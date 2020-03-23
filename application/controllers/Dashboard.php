<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        //load model admin
        $this->load->model('admin');
    }

	public function index()
	{
		if($this->admin->logged_id())
		{
			if($this->session->userdata('user_role') == "Owner"){
				$this->load->view("admin/dashboardOwner");
			}else if($this->session->userdata('user_role') == "Kasir"){
				$this->load->view("admin/dashboardKasir");
			}else{
				$this->load->view("admin/dashboardCS");
			}

		}else{

			//jika session belum terdaftar, maka redirect ke halaman login
			redirect("login");

		}
	}

	public function logout()
	{
		$this->session->sess_destroy();
		
	}

	public function profile()
	{
		$this->load->view("admin/profileUser");
	}

	public function change()
	{
		$this->load->view("admin/change_pass");
	}

	public function change_pass()
	{
		if($this->input->post('change_pass'))
		{
			$password = $this->session->userdata('user_pass');
			$old_pass=$this->input->post('old_pass');
			$new_pass=$this->input->post('new_pass');
			$confirm_pass=$this->input->post('confirm_pass');
			$session_id=$this->session->userdata('user_id');
			$que=$this->db->query("select * from pegawai where id_pegawai='$session_id'");
			$row=$que->row();
			if((!strcmp($old_pass, $password))&& (!strcmp($new_pass, $confirm_pass))){
				$this->admin->change_pass($session_id,$new_pass);
				echo "Password changed successfully !";
				}
			    else{
					echo "Invalid";
				}
		}
		$this->load->view('admin/profileUser');	
	}

}