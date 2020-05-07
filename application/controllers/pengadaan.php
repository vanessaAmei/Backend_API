<?php
use Restserver \Libraries\REST_Controller;

Class Pengadaan extends REST_Controller
{
    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, ContentLength, Accept-Encoding");
        parent::__construct();
        $this->load->model('pengadaan_model'); //Akses Model data nya untuk Controller ini
        $this->load->library('form_validation');
    }

    public function index_get() //Method GET untuk mengambil semua Data pada Database
    {
        $id = $this->get('id_pengadaan');

        if ($id == '') {
            $query = $this->pengadaan_model->getAll();
            echo json_encode($query);
        }else{
            $query = $this->pengadaan_model->getById($id);
            echo json_encode($query);
        }        
    }

    public function codelength_get()
    {
      $kode = $this->get('kode');

      $kode = $this->pengadaan_model->getCodeLength($kode);
      $kode= count($kode);
      if($kode) {
        $this->response($kode, 200);
      }
    }

    public function dataPengadaan_get()
    {
      $status = $this->get('status');

      $status = $this->pengadaan_model->getDataStatus($status);
      if($status) {
        $this->response($status, 200);
      }
    }

    public function searchKode_get()
    {
        $kode = $this->get('kode');
        $search = $this->pengadaan_model->getKode($kode);
        echo json_encode($search);
    }

    public function changeSupplier_post()
    {
        $id = $this->post('id_pengadaan');
        $id_supplier = $this->post('id_supplier');

        $change = $this->pengadaan_model->change_supplier($id, $id_supplier);
        if($change) {
             $this->response($change, 200);
        }
    }

    public function changeStatus_post()
    {
        $id = $this->post('id_pengadaan');
        $status = $this->post('status');

        $change = $this->pengadaan_model->change_status($id, $status);
        if($change) {
             $this->response($change, 200);
        }
    }

    public function index_post($id = null) //Method Post untuk menyimpan Data namun disini juga disamain untuk update, jadi tidak ada method Put
    {
        $validation = $this->form_validation; //Load Form Validation
        $rule = $this->pengadaan_model->rules(); //Mengambil Rules pada Model

        if($id == null) //Jika ID Null yang berarti jika ingin create Data maka Rule nya ini
        {
            array_push($rule,[
                'field' => 'id_supplier',
                'label' => 'id_supplier',
                'rules' => 'required'
            ],
            [
                'field' => 'kode',
                'label' => 'kode',
                'rules' => 'required'
            ],
            [
                'field' => 'tanggal',
                'label' => 'tanggal',
                'rules' => 'required'
            ],
            [
                'field' => 'status',
                'label' => 'status',
                'rules' => 'required'
            ],
            [
                'field' => 'total_harga',
                'label' => 'total_harga',
                'rules' => 'required'
            ],
            );
        }

        else //Jika ID Not Null yang berarti jika ingin update Data maka Rule nya ini
        {
            array_push($rule, [
                'field' => 'id_supplier',
                'label' => 'id_supplier',
                'rules' => 'required'
            ],
            [
                'field' => 'kode',
                'label' => 'kode',
                'rules' => 'required'
            ],
            [
                'field' => 'tanggal',
                'label' => 'tanggal',
                'rules' => 'required'
            ],
            [
                'field' => 'status',
                'label' => 'status',
                'rules' => 'required'
            ],
            [
                'field' => 'total_harga',
                'label' => 'total_harga',
                'rules' => 'required'
            ],
            );
        }

        $validation->set_rules($rule); //Form output untuk menunjukkan hasil rule
        if (!$validation->run()) 
        {
            return $this->returnData($this->form_validation->error_array(), true);
        }

        $tp = new pengadaanData(); //Dibuatkan Entity untuk tempat menyimpan Data, Tidak wajib dilakukan 
        $tp->id_supplier = $this->post('id_supplier');
        $tp->kode = $this->post('kode');
        $tp->tanggal = $this->post('tanggal');
        $tp->status = $this->post('status');
        $tp->total_harga = $this->post('total_harga');
        if($id == null)
        {
            $response = $this->pengadaan_model->store($tp); //Mengakses Fugsi Store dari Model, ini dilakukan jika ID null yang berarti create data
        }
        else
        {
            $response = $this->pengadaan_model->update($tp,$id); //Mengakses Fugsi Update dari Model, ini dilakukan jika ID Not null yang berarti update data
        }
        return $this->returnData($response['msg'], $response['error']);
    }
    
    public function index_delete($id = null) 
    {
        if($id == null)
        {
            return $this->returnData('ID tl Tidak Ditemukan', true); 
        }

        $response = $this->pengadaan_model->destroy($id);
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
Class pengadaanData
{
    public $id_supplier;
    public $kode;
    public $tanggal;
    public $status;
    public $total_harga;
    public $created_at;
    public $updated_at;
}