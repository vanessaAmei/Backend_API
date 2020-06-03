<?php
use Restserver \Libraries\REST_Controller;
Class Pegawai extends REST_Controller
{
    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, ContentLength, Accept-Encoding");
        parent::__construct();
        // $this->load->model("user_model");
        // if($this->user_model->isNotLogin()) redirect(site_url('admin/login'));
        $this->load->model('pegawai_model'); //Akses Model data nya untuk Controller ini
        $this->load->library('form_validation');
    }

    public function index_get() //Method GET untuk mengambil semua Data pada Database
    {
        $id = $this->get('id_pegawai');
        $peran = $this->get('peran');
        
        if ($id == '' && $peran == '') {
            $query = $this->pegawai_model->getAll();
            echo json_encode($query);
        }else if($id == ''){
            $this->db->where('peran', $peran);
            $kontak = $this->db->get('pegawai')->result();
            $this->response($kontak, 200);
        }else if($peran == ''){
            $this->db->where('id_pegawai', $id);
            $kontak = $this->db->get('pegawai')->result();
            $this->response($kontak, 200);
        }
        
    }
    
    public function data_get() //Method GET untuk mengambil semua Data pada Database
    {
            $query = $this->pegawai_model->getAllData();
            echo json_encode($query);
    }

    public function getNameCS() //Method GET untuk mengambil semua Data pada Database
    {
        $query = $this->pegawai_model->getCS();
        echo json_encode($query);
    }

    public function changePassword_post()
    {
        $id = $this->post('id_pegawai');
        $new = $this->post('password');

        $change = $this->pegawai_model->change_pass($id, $new);
        if($change) {
             $this->response($change, 200);
        }
    }

    public function index_post($id = null) //Method Post untuk menyimpan Data namun disini juga disamain untuk update, jadi tidak ada method Put
    {
        $validation = $this->form_validation; //Load Form Validation
        $rule = $this->pegawai_model->rules(); //Mengambil Rules pada Model

        if($id == null) //Jika ID Null yang berarti jika ingin create Data maka Rule nya ini
        {
            array_push($rule, [
				'field' => 'nama',
				'label' => 'nama',
				'rules' => 'required|alpha'
			],
	
			[
				'field' => 'tgl_lahir',
				'label' => 'tgl_lahir',
				'rules' => 'required'
			],
	
			[
				'field' => 'peran',
				'label' => 'peran',
				'rules' => 'required'
			],
	
			[
				'field' => 'alamat',
				'label' => 'alamat',
				'rules' => 'required'
			],
	
			[
				'field' => 'no_telp',
				'label' => 'no_telp',
				'rules' => 'required|numeric|max_length[13]'
			],
	
			[
				'field' => 'username',
				'label' => 'username',
				'rules' => 'required'
			],
	
			[
				'field' => 'password',
				'label' => 'password',
				'rules' => 'required'
			],
            );
        }

        else //Jika ID Not Null yang berarti jika ingin update Data maka Rule nya ini
        {
            array_push($rule, [
				'field' => 'nama',
				'label' => 'nama',
				'rules' => 'required|alpha'
			],
	
			[
				'field' => 'tgl_lahir',
				'label' => 'tgl_lahir',
				'rules' => 'required'
			],
	
			[
				'field' => 'peran',
				'label' => 'peran',
				'rules' => 'required'
			],
	
			[
				'field' => 'alamat',
				'label' => 'alamat',
				'rules' => 'required'
			],
	
			[
				'field' => 'no_telp',
				'label' => 'no_telp',
				'rules' => 'required|numeric|max_length[13]'
			],
	
			[
				'field' => 'username',
				'label' => 'username',
				'rules' => 'required'
			],
	
			[
				'field' => 'password',
				'label' => 'password',
				'rules' => 'required'
			],
            );
        }

        $validation->set_rules($rule); //Form output untuk menunjukkan hasil rule
        if (!$validation->run()) 
        {
            return $this->returnData($this->form_validation->error_array(), true);
        }

        $pegawai = new pegawaiData(); //Dibuatkan Entity untuk tempat menyimpan Data, Tidak wajib dilakukan 
        $pegawai->nama = $this->post('nama');
        $pegawai->tgl_lahir = $this->post('tgl_lahir');
        $pegawai->alamat = $this->post('alamat');
        $pegawai->no_telp = $this->post('no_telp');
        $pegawai->peran = $this->post('peran');
		$pegawai->username = $this->post('username');
		$pegawai->password = $this->post('password'); //Memasukkan Data dari form inputan

        if($id == null)
        {
            $response = $this->pegawai_model->store($pegawai); //Mengakses Fugsi Store dari Model, ini dilakukan jika ID null yang berarti create data
        }
        else
        {
            $response = $this->pegawai_model->update($pegawai,$id); //Mengakses Fugsi Update dari Model, ini dilakukan jika ID Not null yang berarti update data
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    public function index_delete($id = null) //Method Delete untuk menghapus data, namun pada model telah diubah menjadi Update untuk melakukan Soft Delete
    {
        if($id == null)
        {
            return $this->returnData('ID pegawai Tidak Ditemukan', true); //Error Exception jika ID nya tidak ditemukan
        }

        $response = $this->pegawai_model->destroy($id); //Mengakses Fugsi Delete dari Model, melakukan Soft Delete
        return $this->returnData($response['msg'], $response['error']);
    }
    
    public function returnData($msg,$error) //Fungsi untuk me-return kan Nilai balikkan setelah melakukan Method apapun seperti Error dan Pesan
    {
        $response['error']=$error;
        $response['message']=$msg;
        return $this->response($response);
    }
}

//Class Entity Untuk Data
Class pegawaiData
{
    public $nama;
    public $tgl_lahir;
    public $alamat;
    public $no_telp;
    public $peran;
	public $username;
	public $password;
    public $created_at;
    public $updated_at;
    public $deleted_at;
}