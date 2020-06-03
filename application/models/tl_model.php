<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class tl_model extends CI_Model
{
    private $table = 'transaksi_layanan'; 

    public $id_tl;
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
        $this->db->select('a.id_tl as "id_tl", a.id_hewan as "id_hewan", b.id_ukuran_hewan as "id_ukuran_hewan", b.nama as "hewan", a.id_pegawai_k as "id_pegawai_k", c.nama as "Kasir", a.id_pegawai_cs as "id_pegawai_cs", d.nama as "customer_service",
             a.kode as "kode", a.tanggal as "tanggal", 
             a.sub_total as "sub_total", a.total_harga as "total_harga", a.status as "status", a.created_at as "created_at", a.updated_at as "updated_at",
             a.created_by as "created_by", a.updated_by as "updated_by"');
             $this->db->from('transaksi_layanan a');
             $this->db->join('hewan b', 'id_hewan');
             $this->db->join('pegawai c', 'a.id_pegawai_cs = c.id_pegawai');
             $this->db->join('pegawai d', 'a.id_pegawai_cs = d.id_pegawai');
             $this->db->where('a.status =', 'Penjualan');
             return $query = $this->db->get()->result_array();
    }

    public function getBatal() {
        $this->db->select('a.id_tl as "id_tl", a.id_hewan as "id_hewan", b.nama as "hewan", b.id_ukuran_hewan as "id_ukuran_hewan", a.id_pegawai_k as "id_pegawai_k", c.nama as "Kasir", a.id_pegawai_cs as "id_pegawai_cs", d.nama as "customer_service",
             a.kode as "kode", a.tanggal as "tanggal", 
             a.sub_total as "sub_total", a.total_harga as "total_harga", a.status as "status"');
             $this->db->from('transaksi_layanan a');
             $this->db->join('hewan b', 'id_hewan');
             $this->db->join('pegawai c', 'a.id_pegawai_cs = c.id_pegawai');
             $this->db->join('pegawai d', 'a.id_pegawai_cs = d.id_pegawai');
             $this->db->where('a.status =', 'Batal');
             return $query = $this->db->get()->result_array();
    }

    public function getSelesai() {
        $this->db->select('a.id_tl as "id_tl", a.id_hewan as "id_hewan", b.nama as "hewan", b.id_ukuran_hewan as "id_ukuran_hewan", a.id_pegawai_k as "id_pegawai_k", c.nama as "Kasir", a.id_pegawai_cs as "id_pegawai_cs", d.nama as "customer_service",
             a.kode as "kode", a.tanggal as "tanggal", 
             a.sub_total as "sub_total", a.total_harga as "total_harga", a.status as "status"');
             $this->db->from('transaksi_layanan a');
             $this->db->join('hewan b', 'id_hewan');
             $this->db->join('pegawai c', 'a.id_pegawai_cs = c.id_pegawai');
             $this->db->join('pegawai d', 'a.id_pegawai_cs = d.id_pegawai');
             $this->db->where('a.status =', 'Selesai');
             return $query = $this->db->get()->result_array();
    }

    public function getBayar() {
        $this->db->select('a.id_tl as "id_tl", a.id_hewan as "id_hewan", b.id_ukuran_hewan as "id_ukuran_hewan", b.nama as "hewan", a.id_pegawai_k as "id_pegawai_k", c.nama as "Kasir", a.id_pegawai_cs as "id_pegawai_cs", d.nama as "customer_service",
        a.kode as "kode", a.tanggal as "tanggal", 
        a.sub_total as "sub_total", a.total_harga as "total_harga", a.status as "status"');
        $this->db->from('transaksi_layanan a');
        $this->db->join('hewan b', 'id_hewan');
        $this->db->join('pegawai c', 'a.id_pegawai_cs = c.id_pegawai');
        $this->db->join('pegawai d', 'a.id_pegawai_cs = d.id_pegawai');
        $this->db->where('a.status =', 'Pembayaran');
        return $query = $this->db->get()->result_array();
    }

    public function getJenis($id) {
        $query= "SELECT a.nama  FROM jenis_hewan a WHERE a.id_jenis_hewan = (SELECT b.id_jenis_hewan FROM hewan b WHERE b.id_hewan='$id')";
        $result = $this->db->query($query);
        return $result->result();
    }


    public function getByIndex()
    {
        // return $this->db->get_where($this->_table, ["id_tl" => $id])->row();
        $query = "SELECT a.id_tl, b.nama as 'hewan', c.nama as 'Kasir', d.nama as 'customer_service',
        a.kode as 'kode', a.tanggal as 'tanggal', 
        a.sub_total as 'sub_total', a.total_harga as 'total_harga'
        FROM transaksi_layanan a 
        JOIN hewan b ON b.id_hewan=a.id_hewan JOIN pegawai c ON c.id_pegawai=a.id_pegawai_k JOIN pegawai d ON d.id_pegawai=a.id_pegawai_cs";

        $result = $this->db->query($query);
        return $result->result();
    }

    public function change_status($id, $new_status)
    {
            $update_pass=$this->db->query("UPDATE transaksi_layanan SET status='$new_status'  where id_tl='$id'");
            if($update_pass){
                return ['msg'=>'Berhasil Update Status','error'=>false];
            }
    }

    public function number($id)
    {
        $query = "SELECT no_telp FROM customer WHERE id_customer = (SELECT id_customer from hewan WHERE id_hewan=$id)";
        $result = $this->db->query($query);
        return $result->result(); 
    }

    public function bayarUpdate($id, $new_status, $new_total)
    {
            $update_pass=$this->db->query("UPDATE transaksi_layanan SET status='$new_status', total_harga='$new_total' WHERE id_tl='$id'");
            if($update_pass){
                return ['msg'=>'Berhasil Selesai Pembayaran','error'=>false];
            }
    }
    
    public function change_hewan($id, $new_hewan)
    {
            $update_pass=$this->db->query("UPDATE transaksi_layanan SET id_hewan='$new_hewan'  where id_tl='$id'");
            if($update_pass){
                return ['msg'=>'Berhasil Update Hewan','error'=>false];
            }
	}

    public function update_subtotal($id, $new_jumlah)
    {
            $update=$this->db->query("UPDATE transaksi_layanan SET sub_total='$new_jumlah'  where id_tl='$id'");
            if($update){
                return ['msg'=>'Berhasil Update Sub Total','error'=>false];
            }
    }
    
    public function getCodeLength($kode)
    {
        $this->db->select('kode');
        $this->db->from('transaksi_layanan');
        $this->db->like('kode', $kode , 'after');
        return $this->db->get()->result_array(); 
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
        // $this->db->insert_id()
        if($this->db->insert($this->table, $this))
        {
            return ['msg'=>'Berhasil Menambahkan Transaksi Layanan', 'error'=>true];
        }
            return ['msg'=>'Gagal Menambahkan Transaksi Layanan','error'=>true];
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
        
        if($this->db->where('id_tl',$id)->update($this->table, $updateData)) //Query Update dimana data nya yaitu $updateData
        {
            return ['msg'=>'Berhasil Update Transaksi Layanan','error'=>false];
        }
            return ['msg'=>'Gagal Update Transaksi Layanan','error'=>true];
    }
       
    public function destroy($id) { //Fungsi untuk Soft Delete
 
        $this->status = "selesai";
        $updateData = ['status' => $this->status];

        if(empty($this->db->select('*')->where(array('id_tl' => $id))->get($this->table)->row())) 
            return ['msg' => 'Id tidak ditemukan', 'error'=>true];

        if($this->db->where('id_tl',$id)->update($this->table, $updateData)) //Query Update karena akan melakukan Soft Delete sehingga jangan delete melainkan update
        {
            return ['msg'=>'Transaksi Layanan Sudah selesai','error'=>false];
        }
            return ['msg'=>'Transaksi Layanan Gagal','error'=>true];
    }
}
?>