<?php
use Restserver \Libraries\REST_Controller;
Class DetailLayanan extends REST_Controller
{
    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, ContentLength, Accept-Encoding");
        parent::__construct();
        $this->load->model('detailLayanan_model'); //Akses Model data nya untuk Controller ini
        $this->load->library('form_validation');
    }

    public function index_get() //Method GET untuk mengambil semua Data pada Database
    {
        // return $this->returnData($this->db->get('hewan')->result(), false);
        $query = $this->detailLayanan_model->getAll();
        echo json_encode($query);
    }

    public function index_post($id = null) //Method Post untuk menyimpan Data namun disini juga disamain untuk update, jadi tidak ada method Put
    {
        $validation = $this->form_validation; //Load Form Validation
        $rule = $this->detailLayanan_model->rules(); //Mengambil Rules pada Model

        if($id == null) //Jika ID Null yang berarti jika ingin create Data maka Rule nya ini
        {
            array_push($rule, 
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
                    'field' => 'id_layanan',
                    'label' => 'id_layanan',
                    'rules' => 'required'
                ],
                [
                    'field' => 'total',
                    'label' => 'total',
                    'rules' => 'required'
                ],
            );
        }

        else //Jika ID Not Null yang berarti jika ingin update Data maka Rule nya ini
        {
            array_push($rule, 
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
                    'field' => 'id_layanan',
                    'label' => 'id_layanan',
                    'rules' => 'required'
                ],
                [
                    'field' => 'total',
                    'label' => 'total',
                    'rules' => 'required'
                ],
            );
        }

        $validation->set_rules($rule); //Form output untuk menunjukkan hasil rule
        if (!$validation->run()) 
        {
            return $this->returnData($this->form_validation->error_array(), true);
        }

        $detilLayanan = new detilLayananData(); //Dibuatkan Entity untuk tempat menyimpan Data, Tidak wajib dilakukan 
        $detilLayanan->id_ukuran_hewan = $this->post('id_ukuran_hewan');
        $detilLayanan->id_jenis_hewan = $this->post('id_jenis_hewan');
        $detilLayanan->id_layanan = $this->post('id_layanan'); //Memasukkan Data dari form inputan
        $detilLayanan->total = $this->post('total');

        if($id == null)
        {
            $response = $this->detailLayanan_model->store($detilLayanan); //Mengakses Fugsi Store dari Model, ini dilakukan jika ID null yang berarti create data
        }
        else
        {
            $response = $this->detailLayanan_model->update($detilLayanan,$id); //Mengakses Fugsi Update dari Model, ini dilakukan jika ID Not null yang berarti update data
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    public function index_delete($id = null) //Method Delete untuk menghapus data, namun pada model telah diubah menjadi Update untuk melakukan Soft Delete
    {
        if($id == null)
        {
            return $this->returnData('ID hewan Tidak Ditemukan', true); //Error Exception jika ID nya tidak ditemukan
        }

        $response = $this->detailLayanan_model->destroy($id); //Mengakses Fugsi Delete dari Model, melakukan Soft Delete
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
Class detilLayananData
{
    public $id_ukuran_hewan;
    public $id_jenis_hewan;
    public $id_layanan;
    public $total;  
}