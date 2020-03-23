<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class tp_model extends CI_Model
{
    private $table = 'transaksi_produk'; 

    public $id_tp;
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
    
    
    public $rule = [
        [
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
        ]
    ];
    public function Rules() { return $this->rule; } //Fungsi untuk return nilai rule dimana untuk di cek

    public function getAll() {

        // $this->db->select('b.nama as "hewan", c.nama as "Kasir", c.nama as "Customer Service", a.kode as "kode", a.tanggal as "tanggal", a.sub_total as "sub_total", a.total_harga as "total_harga"');
        // $this->db->from('transaksi_produk a'); 
        // $this->db->join('hewan b', 'id_hewan');
        // $this->db->join('pegawai c', 'c.id_pegawai=a.id_pegawai_k', 'c.id_pegawai=a.id_pegawai_cs');
        // $this->db->where('status != "selesai"');
        // return $this->db->get()->result_array();

        $query = "SELECT a.id_tp, b.nama as 'hewan', c.nama as 'Kasir', d.nama as 'Customer Service',
        a.kode as 'kode', a.tanggal as 'tanggal', 
        a.sub_total as 'sub_total', a.total_harga as 'total_harga'
        FROM transaksi_produk a 
        JOIN hewan b ON b.id_hewan=a.id_hewan JOIN pegawai c ON c.id_pegawai=a.id_pegawai_k JOIN pegawai d ON d.id_pegawai=a.id_pegawai_cs WHERE status != 'selesai'";

        $result = $this->db->query($query);
        return $result->result();

    }

    public function store($request) {   //Fungsi untuk menyimpan data
        $this->id_hewan = $request->id_hewan;   //Gunakan $Request untuk mengambil data yang diinputkan oleh user
        $this->id_pegawai_k = $request->id_pegawai_k;
        $this->id_pegawai_cs = $request->id_pegawai_cs;
        $this->kode = $request->kode;
        $this->tanggal = $request->tanggal;
        $this->sub_total = $request->sub_total;
        $this->total_harga = $request->total_harga;
        $this->status = $request->status;
        $this->created_at = date("Y-m-d H:i:s");
        $this->created_by = $request->created_by;  //Mengambil nilai date dari local sesuai format, jadi untuk format ini menggunakan Timestamp
        if($this->db->insert($this->table, $this))
        {
            return ['msg'=>'Berhasil Menambahkan Transaksi Produk','error'=>false];
        }
            return ['msg'=>'Gagal Menambahkan Transaksi Produk','error'=>true];
    }

    public function update($request,$id) { //Fungsi untuk update data

        $this->updated_at = date("Y-m-d H:i:s"); 
        $updateData = ['id_hewan' => $request->id_hewan, 
                        'id_pegawai_k' => $request->id_pegawai_k, 
                        'id_pegawai_cs' => $request->id_pegawai_cs, 
                        'kode' => $request->kode,
                        'tanggal' => $request->tanggal,
                        'sub_total' => $request->sub_total, 
                        'total_harga' => $request->total_harga,
                        'status' => $request->status,
                        'updated_by' => $request->updated_by,
                        'updated_at' => $this->updated_at]; //Memasukan nilai data update terbaru
        
        // if(!empty($_FILES['gambar']['nama'])){
        //     $this->gambar = $this->_uploadImage();
        // }else{
        //     $this->gambar = "default.jpg";
        // }
        
        if($this->db->where('id_tp',$id)->update($this->table, $updateData)) //Query Update dimana data nya yaitu $updateData
        {
            return ['msg'=>'Berhasil Update Transaksi Produk','error'=>false];
        }
            return ['msg'=>'Gagal Update Transaksi Produk','error'=>true];
    }
       
    public function destroy($id) { //Fungsi untuk Soft Delete
 
        $this->status = "selesai";
        $updateData = ['status' => $this->status];

        if(empty($this->db->select('*')->where(array('id_tp' => $id))->get($this->table)->row())) 
            return ['msg' => 'Id tidak ditemukan', 'error'=>true];

        if($this->db->where('id_tp',$id)->update($this->table, $updateData)) //Query Update karena akan melakukan Soft Delete sehingga jangan delete melainkan update
        {
            return ['msg'=>'Bisa Melanjutkan ke Pembayaran','error'=>false];
        }
            return ['msg'=>'Gagal untuk lanjut ke pembayaran','error'=>true];
    }
}
?>