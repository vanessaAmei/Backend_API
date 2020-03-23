<?php
use Restserver \Libraries\REST_Controller;

Class Transaksi_Produk extends REST_Controller
{
    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, ContentLength, Accept-Encoding");
        parent::__construct();
        // $this->load->model("user_model");
        // if($this->user_model->isNotLogin()) redirect(site_url('admin/login'));
        $this->load->model('tp_model'); //Akses Model data nya untuk Controller ini
        $this->load->library('form_validation');
    }

    public function index_get() //Method GET untuk mengambil semua Data pada Database
    {
        // return $this->returnData($this->db->get('produk')->result(), false);
        $query = $this->tp_model->getAll();
        echo json_encode($query);
    }

    public function index_post($id = null) //Method Post untuk menyimpan Data namun disini juga disamain untuk update, jadi tidak ada method Put
    {
        $validation = $this->form_validation; //Load Form Validation
        $rule = $this->tp_model->rules(); //Mengambil Rules pada Model

        if($id == null) //Jika ID Null yang berarti jika ingin create Data maka Rule nya ini
        {
            array_push($rule,[
                'field' => 'id_hewan',
                'label' => 'id_hewan',
                'rules' => 'required'
            ],
    
            [
                'field' => 'id_pegawai_k',
                'label' => 'id_pegawai_k',
                'rules' => 'required'
            ],
    
            [
                'field' => 'id_pegawai_cs',
                'label' => 'id_pegawai_cs',
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
                'field' => 'sub_total',
                'label' => 'sub_total',
                'rules' => 'required'
            ],
            [
                'field' => 'total_harga',
                'label' => 'total_harga',
                'rules' => 'required'
            ],
            [
                'field' => 'status',
                'label' => 'status',
                'rules' => 'required'
            ],
            );
        }

        else //Jika ID Not Null yang berarti jika ingin update Data maka Rule nya ini
        {
            array_push($rule, [
                'field' => 'id_hewan',
                'label' => 'id_hewan',
                'rules' => 'required'
            ],
    
            [
                'field' => 'id_pegawai_k',
                'label' => 'id_pegawai_k',
                'rules' => 'required'
            ],
    
            [
                'field' => 'id_pegawai_cs',
                'label' => 'id_pegawai_cs',
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
                'field' => 'sub_total',
                'label' => 'sub_total',
                'rules' => 'required'
            ],
            [
                'field' => 'total_harga',
                'label' => 'total_harga',
                'rules' => 'required'
            ],
            [
                'field' => 'status',
                'label' => 'status',
                'rules' => 'required'
            ],
            );
        }

        $validation->set_rules($rule); //Form output untuk menunjukkan hasil rule
        if (!$validation->run()) 
        {
            return $this->returnData($this->form_validation->error_array(), true);
        }

        $tp = new tpData(); //Dibuatkan Entity untuk tempat menyimpan Data, Tidak wajib dilakukan 
        $tp->id_hewan = $this->post('id_hewan');
        $tp->id_pegawai_k = $this->post('id_pegawai_k');
        $tp->id_pegawai_cs = $this->post('id_pegawai_cs');
        $tp->kode = $this->post('kode');
        $tp->tanggal = $this->post('tanggal');
        $tp->sub_total = $this->post('sub_total');
        $tp->total_harga = $this->post('total_harga');
        $tp->status = $this->post('status');
         //Memasukkan Data dari form inputan

        if($id == null)
        {
            $response = $this->tp_model->store($tp); //Mengakses Fugsi Store dari Model, ini dilakukan jika ID null yang berarti create data
        }
        else
        {
            $response = $this->tp_model->update($tp,$id); //Mengakses Fugsi Update dari Model, ini dilakukan jika ID Not null yang berarti update data
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    public function index_delete($id = null) //Method Delete untuk menghapus data, namun pada model telah diubah menjadi Update untuk melakukan Soft Delete
    {
        //header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        if($id == null)
        {
            return $this->returnData('ID tp Tidak Ditemukan', true); //Error Exception jika ID nya tidak ditemukan
        }

        $response = $this->tp_model->destroy($id); //Mengakses Fugsi Delete dari Model, melakukan Soft Delete
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
Class tpData
{
    public $id_hewan;
    public $id_pegawai_k;
    public $id_pegawai_cs;
    public $kode;
    public $tanggal;
    public $sub_total;
    public $total_harga;
    public $status;
    public $created_at;
    public $updated_at;
    public $created_by;
    public $updated_by; 
}