<?php
use Restserver \Libraries\REST_Controller;

Class Produk extends REST_Controller
{
    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, ContentLength, Accept-Encoding");
        parent::__construct();
        // $this->load->model("user_model");
        // if($this->user_model->isNotLogin()) redirect(site_url('admin/login'));
        $this->load->model('produk_model'); //Akses Model data nya untuk Controller ini
        $this->load->library('form_validation');
    }

    public function index_get() //Method GET untuk mengambil semua Data pada Database
    {
        
        $query = $this->produk_model->getAll();
        echo json_encode($query);
       
    }

    public function stokHabis_get() //Method GET untuk mengambil semua Data pada Database
    {
        $query = $this->produk_model->getStokHabis();
        echo json_encode($query);
    }

    public function stok_get() //Method GET untuk mengambil semua Data pada Database
    {
        $query = $this->produk_model->getStok();
        echo json_encode($query);
    }

    public function changeJumlah_post()
    {
        $id = $this->post('id_produk');
        $jumlah = $this->post('stok');
        $change = $this->produk_model->change_jumlah($id, $jumlah);
        if($change) {
             $this->response($change, 200);
        }
        // $produk = new produkData();
        // $produk->stok = $this->post('stok');
        // $response = $this->produk_model->change_jumlah($id, $produk);
        // return $this->returnData($response['msg'], $response['error']);

    }

    public function index_post($id = null) //Method Post untuk menyimpan Data namun disini juga disamain untuk update, jadi tidak ada method Put
    {
        $validation = $this->form_validation; //Load Form Validation
        $rule = $this->produk_model->rules(); //Mengambil Rules pada Model

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
                'field' => 'minimal',
                'label' => 'minimal',
                'rules' => 'required'
            ],
    
            [
                'field' => 'stok',
                'label' => 'stok',
                'rules' => 'required'
            ],
            [
                'field' => 'satuan',
                'label' => 'satuan',
                'rules' => 'required'
            ],
            );
        }

        else //Jika ID Not Null yang berarti jika ingin update Data maka Rule nya ini
        {
            array_push($rule, [
                'field' => 'nama',
                'label' => 'nama',
                'rules' => ''
            ],
    
            [
                'field' => 'harga',
                'label' => 'harga',
                'rules' => ''
            ],
    
            [
                'field' => 'minimal',
                'label' => 'minimal',
                'rules' => ''
            ],
    
            [
                'field' => 'stok',
                'label' => 'stok',
                'rules' => ''
            ],
            [
                'field' => 'satuan',
                'label' => 'satuan',
                'rules' => ''
            ],
            );
        }

        $validation->set_rules($rule); //Form output untuk menunjukkan hasil rule
        if (!$validation->run()) 
        {
            return $this->returnData($this->form_validation->error_array(), true);
        }

        $produk = new produkData(); //Dibuatkan Entity untuk tempat menyimpan Data, Tidak wajib dilakukan 
        $produk->nama = $this->post('nama');
        $produk->harga = $this->post('harga');
        $produk->minimal = $this->post('minimal');
        $produk->stok = $this->post('stok');
        $produk->satuan = $this->post('satuan');
        $produk->gambar = $this->post('gambar'); //Memasukkan Data dari form inputan

        if($id == null)
        {
            $response = $this->produk_model->store($produk); //Mengakses Fugsi Store dari Model, ini dilakukan jika ID null yang berarti create data
        }
        else
        {
            $response = $this->produk_model->update($produk,$id); //Mengakses Fugsi Update dari Model, ini dilakukan jika ID Not null yang berarti update data
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    public function index_delete($id = null) //Method Delete untuk menghapus data, namun pada model telah diubah menjadi Update untuk melakukan Soft Delete
    {
        //header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        if($id == null)
        {
            return $this->returnData('ID produk Tidak Ditemukan', true); //Error Exception jika ID nya tidak ditemukan
        }

        $response = $this->produk_model->destroy($id); //Mengakses Fugsi Delete dari Model, melakukan Soft Delete
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
Class produkData
{
    public $id_produk;
    public $nama;
    public $harga;
    public $minimal;
    public $stok;
    public $satuan;
    public $gambar;
    public $create_at;
    public $updated_at;
    public $deleted_at;
}