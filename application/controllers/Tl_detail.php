<?php
use Restserver \Libraries\REST_Controller;

Class Tl_Detail extends REST_Controller
{
    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, ContentLength, Accept-Encoding");
        parent::__construct();
        // $this->load->model("user_model");
        // if($this->user_model->isNotLogin()) redirect(site_url('admin/login'));
        $this->load->model('tl_detail_model'); //Akses Model data nya untuk Controller ini
        $this->load->library('form_validation');
    }

    public function index_get() //Method GET untuk mengambil semua Data pada Database
    {
        $id = $this->get('id_tl');

        if ($id == '') {
            $query = $this->tl_detail_model->getAll();
            echo json_encode($query);
        }else{
            $query = $this->tl_detail_model->getById($id);
            echo json_encode($query);
        } 
    }

    public function index_post($id = null) //Method Post untuk menyimpan Data namun disini juga disamain untuk update, jadi tidak ada method Put
    {
        $validation = $this->form_validation; //Load Form Validation
        $rule = $this->tl_detail_model->rules(); //Mengambil Rules pada Model

        if($id == null) //Jika ID Null yang berarti jika ingin create Data maka Rule nya ini
        {
            array_push($rule,[
                'field' => 'id_tl',
                'label' => 'id_tl',
                'rules' => 'required'
            ],
    
            [
                'field' => 'id_layanan',
                'label' => 'id_layanan',
                'rules' => 'required'
            ],
    
            [
                'field' => 'jumlah',
                'label' => 'jumlah',
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
            array_push($rule, [
                'field' => 'id_tl',
                'label' => 'id_tl',
                'rules' => 'required'
            ],
    
            [
                'field' => 'id_layanan',
                'label' => 'id_layanan',
                'rules' => 'required'
            ],
    
            [
                'field' => 'jumlah',
                'label' => 'jumlah',
                'rules' => 'required'
            ],
            [
                'field' => 'total',
                'label' => 'total',
                'rules' => 'required'
            ],
            );
        }

        $validation->set_rules($rule); //Form outlut untuk menunjukkan hasil rule
        if (!$validation->run()) 
        {
            return $this->returnData($this->form_validation->error_array(), true);
        }

        $tl = new tlData(); //Dibuatkan Entity untuk tempat menyimpan Data, Tidak wajib dilakukan 
        $tl->id_tl = $this->post('id_tl');
        $tl->id_layanan = $this->post('id_layanan');
        $tl->jumlah = $this->post('jumlah');
        $tl->total = $this->post('total');
         //Memasukkan Data dari form inputan

        if($id == null)
        {
            $response = $this->tl_detail_model->store($tl); //Mengakses Fugsi Store dari Model, ini dilakukan jika ID null yang berarti create data
        }
        else
        {
            $response = $this->tl_detail_model->update($tl,$id); //Mengakses Fugsi Update dari Model, ini dilakukan jika ID Not null yang berarti update data
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    public function index_delete($id = null) //Method Delete untuk menghapus data, namun pada model telah diubah menjadi Update untuk melakukan Soft Delete
    {
        //header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        if($id == null)
        {
            return $this->returnData('ID tl Tidak Ditemukan', true); //Error Exception jika ID nya tidak ditemukan
        }

        $response = $this->tl_detail_model->destroy($id); //Mengakses Fugsi Delete dari Model, melakukan Soft Delete
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
Class tlData
{
    public $id_tl;
    public $id_layanan;
    public $jumlah;
    public $total;
}