<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Detailpengadaan_model extends CI_Model
{
    private $table = 'detail_pengadaan'; 

    public $id_detail_p;
    public $id_pengadaan;
    public $id_produk;
    public $jumlah;
    public $total;
    
    public $rule = [
        [
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
        ]
    ];
    public function Rules() { return $this->rule; } //Fungsi untuk return nilai rule dimana untuk di cek

    public function getAll() {

        $this->db->select('a.id_detail_p, a.id_pengadaan, b.nama as "nama", b.harga as "harga", b.satuan as "satuan", a.id_produk as "id_produk", a.jumlah as "jumlah", a.total as "total"');
        $this->db->from('detail_pengadaan a'); 
        $this->db->join('produk b', 'id_produk');
        return $this->db->get()->result_array();
    }

    public function getById($id)
    {
        $this->db->select('a.id_detail_p, a.id_pengadaan, b.nama as "nama", b.harga as "harga", b.satuan as "satuan", a.id_produk as "id_produk", a.jumlah as "jumlah",  a.total as "total"');
        $this->db->from('detail_pengadaan a'); 
        $this->db->join('produk b', 'id_produk');
        $this->db->where('id_pengadaan',$id);
        return $this->db->get()->result_array();
    }

    public function getID($id,$produk){
        $query = "SELECT id_detail_p FROM detail_pengadaan WHERE id_pengadaan = $id AND id_produk = $produk";
        $result = $this->db->query($query);
        return $result->result();   
    }

    public function change_total($id, $new_total)
    {
            $update=$this->db->query("UPDATE pengadaan SET total_harga='$new_total'  where id_pengadaan='$id'");
            if($update){
                return ['msg'=>'Berhasil Update Total','error'=>false];
            }
    }

    public function change_jumlah($id, $new_jumlah, $harga)
    {
            $update=$this->db->query("UPDATE detail_pengadaan SET jumlah='$new_jumlah', total=($harga*$new_jumlah)  where id_detail_p='$id'");
            if($update){
                return ['msg'=>'Berhasil Update Jumlah','error'=>false];
            }
    }

    public function searchProduk($id){
        $query = "SELECT * FROM produk WHERE deleted_at IS NULL AND id_produk NOT IN (SELECT id_produk from detail_pengadaan WHERE id_pengadaan=$id)";
        $result = $this->db->query($query);
        return $result->result(); 
    }
    

    public function store($request) {   //Fungsi untuk menyimpan data
        $this->id_pengadaan = $request->id_pengadaan; 
        $this->id_produk = $request->id_produk;
        $this->jumlah = $request->jumlah;
        $this->total = $request->total;
        if($this->db->insert($this->table, $this))
        {
            return ['msg'=>'Berhasil Menambahkan Detail','error'=>false];
        }
            return ['msg'=>'Gagal Menambahkan Detail','error'=>true];
    }

    public function update($request,$id) { //Fungsi untuk update data
        $updateData = ['id_pengadaan' => $request->id_pengadaan, 
                        'id_produk' => $request->id_produk, 
                        'jumlah' => $request->jumlah,
                        'total' => $request->total]; 

        if($this->db->where('id_detail_p',$id)->update($this->table, $updateData))
        {
            return ['msg'=>'Berhasil Update Detail','error'=>false];
        }
            return ['msg'=>'Gagal Update Detail','error'=>true];
    }
       
    public function destroy($id) { //Fungsi untuk Soft Delete

        if(empty($this->db->select('*')->where(array('id_detail_p' => $id))->get($this->table)->row())) 
            return ['msg' => 'Id tidak ditemukan', 'error'=>true];

        if($this->db->delete($this->table, array('id_detail_p'=> $id))){
            return ['msg' => 'Berhasil', 'error'=>false];
        }
        return ['msg' => 'Gagal', 'error'=>true];
    }
    
}
?>