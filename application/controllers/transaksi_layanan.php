<?php
use Restserver \Libraries\REST_Controller;

Class Transaksi_Layanan extends REST_Controller
{
    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, ContentLength, Accept-Encoding");
        parent::__construct();
        $this->load->model('tl_model'); //Akses Model data nya untuk Controller ini
        $this->load->library('form_validation');
    }

    public function index_get() //Method GET untuk mengambil semua Data pada Database
    {
        $id = $this->get('id_tl');

        if ($id == '') {
            $query = $this->tl_model->getAll();
            echo json_encode($query);
        }else if($id == 0){
            $kontak = $this->db->count_all('transaksi_layanan');
            $this->response($kontak, 200);
        }else{
            $query = $this->tl_model->getByIndex();
            echo json_encode($query);
        }        
    }

    public function dataBayar_get(){
        $query = $this->tl_model->getBayar();
        echo json_encode($query);
    }

    public function number_get(){
        $id_hewan = $this->get('id_hewan');

        $change = $this->tl_model->number($id_hewan);
        if($change) {
             $this->response($change, 200);
        }
    }

    public function dataBatal_get(){
        $query = $this->tl_model->getBatal();
        echo json_encode($query);
    }

    public function dataSelesai_get(){
        $query = $this->tl_model->getSelesai();
        echo json_encode($query);
    }

    // public function dataSelesai_get(){
    //     $query = $this->tl_model->getSelesai();
    //     echo json_encode($query);
    // }

    public function updateHewan_post()
    {       
        $id = $this->post('id_tl');
        $id_hewan = $this->post('id_hewan');

        $change = $this->tl_model->change_hewan($id, $id_hewan);
        if($change) {
             $this->response($change, 200);
        }
    }

    public function changeStatus_post()
    {
        $id = $this->post('id_tl');
        $status = $this->post('status');

        $change = $this->tl_model->change_status($id, $status);
        if($change) {
             $this->response($change, 200);
        }
    }

    public function updateBayar_post()
    {
        $id = $this->post('id_tl');
        $status = $this->post('status');
        $total = $this->post('total');

        $change = $this->tl_model->bayarUpdate($id, $status, $total);
        if($change) {
             $this->response($change, 200);
        }
    }

    public function updateSub_post()
    {
        $id = $this->post('id_tl');
        $jumlah = $this->post('sub_total');

        $change = $this->tl_model->update_subtotal($id, $jumlah);
        if($change) {
             $this->response($change, 200);
        }
    }

    public function codelength_get()
    {
      $kode = $this->get('kode');

      $transaksi_produk = $this->tl_model->getCodeLength($kode);
      $transaksi_produk = count($transaksi_produk);
      if($transaksi_produk) {
        $this->response($transaksi_produk, 200);
      }
    }

    public function index_post($id = null) //Method Post untuk menyimpan Data namun disini juga disamain untuk update, jadi tidak ada method Put
    {
        $validation = $this->form_validation; //Load Form Validation
        $rule = $this->tl_model->rules(); //Mengambil Rules pada Model

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
                'field' => 'total',
                'label' => 'total',
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
                'field' => 'total',
                'label' => 'total',
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

        $tp = new tlData(); //Dibuatkan Entity untuk tempat menyimpan Data, Tidak wajib dilakukan 
        $tp->id_hewan = $this->post('id_hewan');
        $tp->id_pegawai_k = $this->post('id_pegawai_k');
        $tp->id_pegawai_cs = $this->post('id_pegawai_cs');
        $tp->kode = $this->post('kode');
        $tp->tanggal = $this->post('tanggal');
        $tp->sub_total = $this->post('sub_total');
        $tp->total = $this->post('total');
        $tp->status = $this->post('status');
        $tp->created_by = $this->post('created_by');
         //Memasukkan Data dari form inputan

        if($id == null)
        {
            $response = $this->tl_model->store($tp); //Mengakses Fugsi Store dari Model, ini dilakukan jika ID null yang berarti create data
        }
        else
        {
            $response = $this->tl_model->update($tp,$id); //Mengakses Fugsi Update dari Model, ini dilakukan jika ID Not null yang berarti update data
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

        $response = $this->tl_model->destroy($id); //Mengakses Fugsi Delete dari Model, melakukan Soft Delete
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
    public $id_hewan;
    public $id_pegawai_k;
    public $id_pegawai_cs;
    public $kode;
    public $tanggal;
    public $sub_total;
    public $total;
    public $status;
    public $created_at;
    public $updated_at;
    public $created_by;
    public $updated_by; 
}