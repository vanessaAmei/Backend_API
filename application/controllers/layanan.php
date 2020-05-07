<?php
use Restserver \Libraries\REST_Controller;
Class Layanan extends REST_Controller
{
    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, ContentLength, Accept-Encoding");
        parent::__construct();
        $this->load->model('layanan_model'); //Akses Model data nya untuk Controller ini
        $this->load->library('form_validation');
        $this->load->library('smsgateway'); 
    }

    public function index_get() //Method GET untuk mengambil semua Data pada Database
    {
        $id = $this->get('id_layanan');

        if ($id == '') {
            $query = $this->layanan_model->getAll();
            echo json_encode($query);
        }else{
            $query = $this->layanan_model->getById($id);
            echo json_encode($query);
        } 
    }

    public function layananUkuran_get()
    {
      $id_ukuran_hewan = $this->get('id_ukuran_hewan');

      $layanan = $this->layanan_model->getLayanan($id_ukuran_hewan);
      if($layanan) {
        $this->response($layanan, 200);
      }
    }

    public function kirimsms_post() {
        $to = $this->post('no_hp');
        $message = $this->post('pesan');
        $deviceID = 117078;
        $options = [];
       
  
        $result = $this->smsgateway->sendMessageToNumber($to, $message, $deviceID, $options);
        print_r($result);
      }

    public function index_post($id = null) //Method Post untuk menyimpan Data namun disini juga disamain untuk update, jadi tidak ada method Put
    {
        $validation = $this->form_validation; //Load Form Validation
        $rule = $this->layanan_model->rules(); //Mengambil Rules pada Model

        if($id == null) //Jika ID Null yang berarti jika ingin create Data maka Rule nya ini
        {
            array_push($rule, [
                'field' => 'nama',
                'label' => 'nama',
                'rules' => 'required'
            ],
            [
                'field' => 'harga',
                'label' => 'harga',
                'rules' => 'required'
            ],
            [
                'field' => 'id_ukuran_hewan',
                'label' => 'id_ukuran_hewan',
                'rules' => 'required'
            ],
            );
        }

        else //Jika ID Not Null yang berarti jika ingin update Data maka Rule nya ini
        {
            array_push($rule, [
                'field' => 'nama',
                'label' => 'nama',
                'rules' => 'required'
            ],
            [
                'field' => 'harga',
                'label' => 'harga',
                'rules' => 'required'
            ],
            [
                'field' => 'id_ukuran_hewan',
                'label' => 'id_ukuran_hewan',
                'rules' => 'required'
            ]
            );
        }

        $validation->set_rules($rule); //Form output untuk menunjukkan hasil rule
        if (!$validation->run()) 
        {
            return $this->returnData($this->form_validation->error_array(), true);
        }

        $layanan = new layananData(); //Dibuatkan Entity untuk tempat menyimpan Data, Tidak wajib dilakukan 
        $layanan->nama = $this->post('nama');//Memasukkan Data dari form inputan
        $layanan->harga = $this->post('harga');
        $layanan->id_ukuran_hewan = $this->post('id_ukuran_hewan');

        if($id == null)
        {
            $response = $this->layanan_model->store($layanan); 
            //Mengakses Fugsi Store dari Model, ini dilakukan jika ID null yang berarti create data
        }
        else
        {
            $response = $this->layanan_model->update($layanan,$id); //Mengakses Fugsi Update dari Model, ini dilakukan jika ID Not null yang berarti update data
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    public function index_delete($id = null) //Method Delete untuk menghapus data, namun pada model telah diubah menjadi Update untuk melakukan Soft Delete
    {
        if($id == null)
        {
            return $this->returnData('ID Layanan Tidak Ditemukan', true); //Error Exception jika ID nya tidak ditemukan
        }

        $response = $this->layanan_model->destroy($id); //Mengakses Fugsi Delete dari Model, melakukan Soft Delete
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
Class layananData
{
    public $nama;
    public $harga;
    public $id_ukuran_hewan;
    public $created_at;
    public $updated_at;
    public $deleted_at; 
}