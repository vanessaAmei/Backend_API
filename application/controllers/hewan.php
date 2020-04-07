<?php
use Restserver \Libraries\REST_Controller;
Class Hewan extends REST_Controller
{
    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, ContentLength, Accept-Encoding");
        parent::__construct();
        $this->load->model('hewan_model'); //Akses Model data nya untuk Controller ini
        $this->load->library('form_validation');
    }

    public function index_get() //Method GET untuk mengambil semua Data pada Database
    {
        // // return $this->returnData($this->db->get('hewan')->result(), false);
        // $query = $this->hewan_model->getAll();
        // echo json_encode($query);
        $id = $this->get('id_hewan');

        if ($id == '') {
            $query = $this->hewan_model->getAll();
            echo json_encode($query);
        }else{
            $query = $this->hewan_model->getById($id);
            echo json_encode($query);
        } 
    }

    public function index_post($id = null) //Method Post untuk menyimpan Data namun disini juga disamain untuk update, jadi tidak ada method Put
    {
        $validation = $this->form_validation; //Load Form Validation
        $rule = $this->hewan_model->rules(); //Mengambil Rules pada Model

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
                'field' => 'id_jenis_hewan',
                'label' => 'id_jenis_hewan',
                'rules' => 'required'
            ],
    
            [
                'field' => 'id_ukuran_hewan',
                'label' => 'id_ukuran_hewan',
                'rules' => 'required'
            ],
    
            [
                'field' => 'id_customer',
                'label' => 'id_customer',
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
                'field' => 'id_jenis_hewan',
                'label' => 'id_jenis_hewan',
                'rules' => 'required'
            ],
    
            [
                'field' => 'id_ukuran_hewan',
                'label' => 'id_ukuran_hewan',
                'rules' => 'required'
            ],
    
            [
                'field' => 'id_customer',
                'label' => 'id_customer',
                'rules' => 'required'
            ],
            );
        }

        $validation->set_rules($rule); //Form output untuk menunjukkan hasil rule
        if (!$validation->run()) 
        {
            return $this->returnData($this->form_validation->error_array(), true);
        }

        $hewan = new hewanData(); //Dibuatkan Entity untuk tempat menyimpan Data, Tidak wajib dilakukan 
        $hewan->nama = $this->post('nama');
        $hewan->tgl_lahir = $this->post('tgl_lahir');
        $hewan->id_ukuran_hewan = $this->post('id_ukuran_hewan');
        $hewan->id_jenis_hewan = $this->post('id_jenis_hewan');
        $hewan->id_customer = $this->post('id_customer'); //Memasukkan Data dari form inputan

        if($id == null)
        {
            $response = $this->hewan_model->store($hewan); //Mengakses Fugsi Store dari Model, ini dilakukan jika ID null yang berarti create data
        }
        else
        {
            $response = $this->hewan_model->update($hewan,$id); //Mengakses Fugsi Update dari Model, ini dilakukan jika ID Not null yang berarti update data
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    public function index_delete($id = null) //Method Delete untuk menghapus data, namun pada model telah diubah menjadi Update untuk melakukan Soft Delete
    {
        if($id == null)
        {
            return $this->returnData('ID hewan Tidak Ditemukan', true); //Error Exception jika ID nya tidak ditemukan
        }

        $response = $this->hewan_model->destroy($id); //Mengakses Fugsi Delete dari Model, melakukan Soft Delete
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
Class hewanData
{
    public $nama;
    public $tgl_lahir;
    public $id_ukuran_hewan;
    public $id_jenis_hewan;
    public $id_customer;
    public $created_at;
    public $updated_at;
    public $deleted_at;
    public $created_by;
    public $updated_by;
    public $deleted_by;  
}