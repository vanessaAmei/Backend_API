<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Pengadaan_model extends CI_Model
{
    private $table = 'pengadaan'; 

    public $id_pengadaan;
    public $id_supplier;
    public $kode;
    public $tanggal;
    public $status;
    public $total_harga;
    public $created_at;
    public $updated_at;

    
    public $rule = [
        [
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
        ]
    ];
    public function Rules() { return $this->rule; } //Fungsi untuk return nilai rule dimana untuk di cek

    public function getAll() {
        $this->db->select('a.id_pengadaan as "id_pengadaan", a.id_supplier as "id_supplier", b.nama as "supplier", b.no_telp as "no_telp", b.alamat as "alamat", a.kode as "kode", a.tanggal as "tanggal", a.status as "status", a.total_harga as "total_harga"');
        $this->db->from('pengadaan a');
        $this->db->join('supplier b', 'id_supplier');
        $this->db->where('a.status="Proses"');        
        return $this->db->get()->result_array();
    }

    public function getDataStatus($status) {
        $this->db->select('a.id_pengadaan as "id_pengadaan", a.id_supplier as "id_supplier", b.nama as "supplier", b.no_telp as "no_telp", b.alamat as "alamat", a.kode as "kode", a.tanggal as "tanggal", a.status as "status", a.total_harga as "total_harga"');
        $this->db->from('pengadaan a');
        $this->db->join('supplier b', 'id_supplier');
        $this->db->where('a.status=', $status);        
        return $this->db->get()->result_array();
    }

    public function getById($id)
    {
        $this->db->select('a.id_pengadaan as "id_pengadaan", a.id_supplier as "id_supplier", b.nama as "supplier", b.no_telp as "no_telp", b.alamat as "alamat", a.kode as "kode", a.tanggal as "tanggal", a.status as "status", a.total_harga as "total_harga"');
        $this->db->from('pengadaan a');
        $this->db->join('supplier b', 'id_supplier');
        $this->db->where('id_pengadaan',$id);
        return $this->db->get()->result_array();
    }

    public function getCodeLength($kode)
    {
        $this->db->select('kode');
        $this->db->from('pengadaan');
        $this->db->like('kode', $kode , 'after');
        return $this->db->get()->result_array(); 
    }

    public function getKode($kode){
        $query = "SELECT id_pengadaan FROM pengadaan WHERE kode = '$kode'";
        $result = $this->db->query($query);
        return $result->result();   
    }

    public function change_supplier($id, $new_supplier)
    {
            $update=$this->db->query("UPDATE pengadaan SET id_supplier='$new_supplier'  where id_pengadaan='$id'");
            if($update){
                return ['msg'=>'Berhasil Update Supplier','error'=>false];
            }
    }

    public function change_status($id, $new_status)
    {
            $update=$this->db->query("UPDATE pengadaan SET status='$new_status'  where id_pengadaan='$id'");
            if($update){
                return ['msg'=>'Berhasil Update Status','error'=>false];
            }
    }

    public function store($request) {   //Fungsi untuk menyimpan data
        $this->id_supplier = $request->id_supplier;
        $this->kode = $request->kode;
        $this->tanggal = $request->tanggal;
        $this->status = $request->status;
        $this->total_harga = $request->total_harga;
        $this->created_at = date("Y-m-d H:i:s"); 
        if($this->db->insert($this->table, $this))
        {
            return ['msg'=>'Berhasil Input Pengadaan','error'=>false];
        }
            return ['msg'=>'Gagal Input Pengadaan','error'=>true];
    }

    public function destroy($id) { //Fungsi untuk Soft Delete

        if(empty($this->db->select('*')->where(array('id_pengadaan' => $id))->get($this->table)->row())) 
            return ['msg' => 'Id tidak ditemukan', 'error'=>true];

        if($this->db->delete($this->table, array('id_pengadaan'=> $id))){
            return ['msg' => 'Berhasil', 'error'=>false];
        }
        return ['msg' => 'Gagal', 'error'=>true];
    }

    public function update($request,$id) { //Fungsi untuk update data

        $this->updated_at = date("Y-m-d H:i:s"); 
        $updateData = [ 'id_supplier' => $request->id_supplier ,
                        'kode' => $request->kode, 
                        'tanggal' => $request->tanggal, 
                        'status' => $request->status, 
                        'total_harga' => $request->total_harga, 
                        'updated_at' => $this->updated_at]; //Memasukan nilai data update terbaru

        if($this->db->where('id_pengadaan',$id)->update($this->table, $updateData)) //Query Update dimana data nya yaitu $updateData
        {
            return ['msg'=>'Berhasil Update Pengadaan','error'=>false];
        }
            return ['msg'=>'Gagal Update Pengadaan','error'=>true];
    }
}
?>