<?php
use Restserver \Libraries\REST_Controller;

Class Detail_pengadaan extends REST_Controller
{
    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, ContentLength, Accept-Encoding");
        parent::__construct();
        $this->load->model('detailpengadaan_model');
        $this->load->library('form_validation');
    }

    public function index_get() 
    {
        $id = $this->get('id_pengadaan');

        if ($id == '') {
            $query = $this->detailpengadaan_model->getAll();
            echo json_encode($query);
        }else{
            $query = $this->detailpengadaan_model->getById($id);
            echo json_encode($query);
        } 
    }

    public function searchDetail_get()
    {
        $id = $this->get('id_pengadaan');
        $produk = $this->get('id_produk');
        $search = $this->detailpengadaan_model->getID($id, $produk);
        echo json_encode($search);
    }

    public function getProduk_get(){
        $id = $this->get('id_pengadaan');

        $search = $this->detailpengadaan_model->searchProduk($id);
        if($search) {
             $this->response($search, 200);
        }
    }

    public function changeTotal_post()
    {
        $id = $this->post('id_pengadaan');
        $total = $this->post('total_harga');

        $change = $this->detailpengadaan_model->change_total($id, $total);
        if($change) {
             $this->response($change, 200);
        }
    }

    public function changeJumlah_post()
    {
        $id = $this->post('id_detail_p');
        $jumlah = $this->post('jumlah');
        $harga = $this->post('harga');

        $change = $this->detailpengadaan_model->change_jumlah($id, $jumlah, $harga);
        if($change) {
             $this->response($change, 200);
        }
    }

    public function index_post($id = null) 
    {
        $validation = $this->form_validation;
        $rule = $this->detailpengadaan_model->rules(); 

        if($id == null) 
        {
            array_push($rule,[
                'field' => 'id_pengadaan',
                'label' => 'id_pengadaan',
                'rules' => 'required'
            ],
    
            [
                'field' => 'id_produk',
                'label' => 'id_produk',
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
                'field' => 'id_pengadaan',
                'label' => 'id_pengadaan',
                'rules' => 'required'
            ],
    
            [
                'field' => 'id_produk',
                'label' => 'id_produk',
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

        $validation->set_rules($rule);
        if (!$validation->run()) 
        {
            return $this->returnData($this->form_validation->error_array(), true);
        }

        $tl = new pengadaanData();
        $tl->id_pengadaan = $this->post('id_pengadaan');
        $tl->id_produk = $this->post('id_produk');
        $tl->jumlah = $this->post('jumlah');
        $tl->total = $this->post('total');

        if($id == null)
        {
            $response = $this->detailpengadaan_model->store($tl);
        }
        else
        {
            $response = $this->detailpengadaan_model->update($tl,$id); 
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    public function index_delete($id = null) 
    {
        if($id == null)
        {
            return $this->returnData('ID tl Tidak Ditemukan', true); 
        }

        $response = $this->detailpengadaan_model->destroy($id);
        return $this->returnData($response['msg'], $response['error']);
    }
    
    public function returnData($msg,$error)
    {
        $response['error']=$error;
        $response['message']=$msg;
        return $this->response($response);
    }
}

//Class Entity Untuk Data
Class pengadaanData
{
    public $id_pengadaan;
    public $id_produk;
    public $jumlah;
}