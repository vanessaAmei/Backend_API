<?php
use Restserver \Libraries\REST_Controller;

Class Customer extends REST_Controller
{
    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, ContentLength, Accept-Encoding");
        parent::__construct();
        $this->load->model('customer_model'); //Akses Model data nya untuk Controller ini
        $this->load->library('form_validation');
    }

    public function index_get() //Method GET untuk mengambil semua Data pada Database
    {
        // return $this->customer_model->getAll();
        // return $this->returnData($this->db->get('customer')->result(), false);
        $query = $this->customer_model->getAll();
        echo json_encode($query);
    }

    public function data_get() //Method GET untuk mengambil semua Data pada Database
    {
        // return $this->customer_model->getAll();
        // return $this->returnData($this->db->get('customer')->result(), false);
        $query = $this->customer_model->getAllData();
        echo json_encode($query);
    }

    public function index_post($id = null) //Method Post untuk menyimpan Data namun disini juga disamain untuk update, jadi tidak ada method Put
    {
        $validation = $this->form_validation; //Load Form Validation
        $rule = $this->customer_model->rules(); //Mengambil Rules pada Model

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
                'field' => 'alamat',
                'label' => 'alamat',
                'rules' => 'required'
            ],
    
            [
                'field' => 'no_telp',
                'label' => 'no_telp',
                'rules' => 'required|numeric|max_length[13]'
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
                'field' => 'alamat',
                'label' => 'alamat',
                'rules' => 'required'
            ],
    
            [
                'field' => 'no_telp',
                'label' => 'no_telp',
                'rules' => 'required|numeric|max_length[13]'
            ],
            );
        }

        $validation->set_rules($rule); //Form output untuk menunjukkan hasil rule
        if (!$validation->run()) 
        {
            return $this->returnData($this->form_validation->error_array(), true);
        }

        $customer = new customerData(); //Dibuatkan Entity untuk tempat menyimpan Data, Tidak wajib dilakukan 
        $customer->nama = $this->post('nama');
        $customer->tgl_lahir = $this->post('tgl_lahir');
        $customer->alamat = $this->post('alamat');
        $customer->no_telp = $this->post('no_telp'); //Memasukkan Data dari form inputan

        if($id == null)
        {
            $response = $this->customer_model->store($customer); //Mengakses Fugsi Store dari Model, ini dilakukan jika ID null yang berarti create data
        }
        else
        {
            $response = $this->customer_model->update($customer,$id); //Mengakses Fugsi Update dari Model, ini dilakukan jika ID Not null yang berarti update data
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    public function index_delete($id = null) //Method Delete untuk menghapus data, namun pada model telah diubah menjadi Update untuk melakukan Soft Delete
    {
        if($id == null)
        {
            return $this->returnData('ID customer Tidak Ditemukan', true); //Error Exception jika ID nya tidak ditemukan
        }

        $response = $this->customer_model->destroy($id); //Mengakses Fugsi Delete dari Model, melakukan Soft Delete
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
Class customerData
{
    public $nama;
    public $tgl_lahir;
    public $alamat;
    public $no_telp;
    public $username;
    public $password;
    public $created_at;
    public $updated_at;
    public $deleted_at; 
    public $created_by;
    public $updated_by;
    public $deleted_by; 
}