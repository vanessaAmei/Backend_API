<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class produk_model extends CI_Model
{
    private $table = 'produk'; 

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
    
    
    public $rule = [
        [
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
        ]
    ];
    public function Rules() { return $this->rule; } //Fungsi untuk return nilai rule dimana untuk di cek

    public function getAll() {
        $this->db->select('*');
        $this->db->from('produk');
        $this->db->where('deleted_at IS NULL');
        return $this->db->get()->result_array();
    }

    public function getStokHabis() {
        $this->db->select('*');
        $this->db->from('produk');
        $this->db->where('deleted_at IS NULL');
        $this->db->where('stok<=minimal');
        return $this->db->get()->result_array();
    }

    public function getStok() {
        $this->db->select('*');
        $this->db->from('produk');
        $this->db->where('deleted_at IS NULL');
        $this->db->where('stok>=minimal');
        return $this->db->get()->result_array();
    }

    public function change_jumlah($id, $new_jumlah)
    {
        $update=$this->db->query("UPDATE produk SET stok=stok+$new_jumlah WHERE id_produk='$id'");
        if($update){
            return ['msg'=>'Berhasil Update Jumlah','error'=>false];
        }
        // $this->updated_at = date("Y-m-d H:i:s"); 
        // $this->stok = $request->stok;
        // $updateData = [ 'stok' => $request->stok,
        //                 'updated_at' => $this->updated_at]; 
        
        // if($this->db->where('id_produk',$id)->update($this->table, $updateData)) 
        // {
        //     return ['msg'=>'Berhasil Update Produk','error'=>false];
        // }
        //     return ['msg'=>'Gagal Update Produk','error'=>true];
    }

    public function store($request) {   //Fungsi untuk menyimpan data
        $this->nama = $request->nama;   //Gunakan $Request untuk mengambil data yang diinputkan oleh user
        $this->harga = $request->harga;
        $this->stok = $request->stok;
        $this->minimal = $request->minimal;
        $this->satuan = $request->satuan;
        $this->gambar = $this->_uploadImage();
        $this->create_at = date("Y-m-d H:i:s"); //Mengambil nilai date dari local sesuai format, jadi untuk format ini menggunakan Timestamp
        if($this->db->insert($this->table, $this))
        {
            return ['msg'=>'Berhasil Input Produk','error'=>false];
        }
            return ['msg'=>'Gagal Input Produk','error'=>true];
    }

    public function update($request,$id) { //Fungsi untuk update data

        $this->updated_at = date("Y-m-d H:i:s"); 
        $this->nama = $request->nama;
        $this->gambar = $this->_uploadImage();
        $updateData = ['nama' => $this->nama, 
                        'harga' => $request->harga, 
                        'stok' => $request->stok, 
                        'minimal' => $request->minimal,
                        'satuan' => $request->satuan, 
                        'gambar' => $this->gambar,
                        'updated_at' => $this->updated_at]; 
        
        if($this->db->where('id_produk',$id)->update($this->table, $updateData)) 
        {
            return ['msg'=>'Berhasil Update Produk','error'=>false];
        }
            return ['msg'=>'Gagal Update Produk','error'=>true];
    }
       
    public function destroy($id) { //Fungsi untuk Soft Delete
 
        // $this->_deleteImage($id);
        $this->deleted_at = date("Y-m-d H:i:s");
        $updateData = ['deleted_at' => $this->deleted_at];

        if(empty($this->db->select('*')->where(array('id_produk' => $id))->get($this->table)->row())) 
            return ['msg' => 'Id tidak ditemukan', 'error'=>true];

        if($this->db->where('id_produk',$id)->update($this->table, $updateData)) //Query Update karena akan melakukan Soft Delete sehingga jangan delete melainkan update
        {
            return ['msg'=>'Berhasil Hapus Produk','error'=>false];
        }
            return ['msg'=>'Gagal Hapus Produk','error'=>true];

        

        // if($this->db->delete($this->table, array('id'=> $id))){
        //     return ['msg' => 'Berhasil', 'error'=>false];
        // }
        // return ['msg' => 'Gagal', 'error'=>true];
    }

    private function _uploadImage()
    {
        
        $config['upload_path']          = './upload/';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['file_name']            = $this->nama;
        $config['overwrite']			= true;
        $config['max_size']             = 1024; // 1MB
        // $config['max_width']            = 1000;
        // $config['max_height']           = 1000;

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('gambar')) {
            return $this->upload->data("file_name");
        }

        return "default.jpg";
    }

    private function _deleteImage($id)
    {
        $product = $this->getById($id);
        if ($product->gambar != "default.jpg") {
            $filename = explode(".", $product->gambar)[0];
            return array_map('unlink', glob(FCPATH."upload/$filename.*"));
        }
    }
}
?>