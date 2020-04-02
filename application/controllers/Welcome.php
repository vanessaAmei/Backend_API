<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	function __construct()
	{
		parent::__construct();
    	$this->load->model('home_model');

    	if($this->session->userdata('logged_in') === TRUE)
    	{
    		$remove_space = str_replace(' ', '', $this->session->userdata('PERAN'));

      		redirect('Page/'.$remove_space);
		}
		
	}

	function login(){
		$status = (isset($_GET['status']) AND trim($_GET['status'])!='')?$_GET['status']:200;
        $msg = (isset($_GET['msg']) AND trim($_GET['msg'])!='')?base64_decode($_GET['msg']):'';
		
		$data['status'] = $status;
		$data['msg'] = $msg;
		
		$this->load->view('admin/loginPage',$data);

	}
 

	// public function index()
	// {	
	// 	$status = (isset($_GET['status']) AND trim($_GET['status'])!='')?$_GET['status']:200;
    //     $msg = (isset($_GET['msg']) AND trim($_GET['msg'])!='')?base64_decode($_GET['msg']):'';
		
	// 	$data['status'] = $status;
	// 	$data['msg'] = $msg;
		
	// 	$this->load->view('admin/loginPage',$data);
	// }

	public function auth()
	{
    	$username = $this->input->post('username',TRUE);
		// $password = md5($this->input->post('password',TRUE));
		$password = $this->input->post('password',TRUE);
    	$validate_user = $this->home_model->validate_user($username,$password);

    	if($validate_user->num_rows() > 0)
	    {
			$status = 200;
			$msg = 'success';
			
	        $data_user  = $validate_user->row_array();
			$username  = $data_user['username'];
			$id_pegawai = $data_user['id_pegawai'];
			$nama = $data_user['nama'];
			$peran = $data_user['peran'];

	        $validate_user_role = $this->home_model->validate_user_role($peran);

	        $data_user_role = $validate_user_role->row_array();
			$user_role = $data_user_role['peran'];

	        $sesdata = array(
				'id_pegawai' => $id_pegawai,
				'username'  => $username,
				'nama' => $nama,
	            'peran'     => $peran,
	            'logged_in' => TRUE
			);
			
	        $this->session->set_userdata($sesdata);

			$remove_space = str_replace(' ', '', $user_role);
			
	        redirect('index.php/Page/'.$remove_space);
    	}
    	else
    	{
			$status = 500;
			$msg = base64_encode('Invalid username or password');

			redirect(base_url().'status='.$status.'&msg='.$msg);
		}
  	}

	function invalid()
	{
		echo "compiled ";
	}

	public function index()
	{
		$this->load->view('login');
	}
}
