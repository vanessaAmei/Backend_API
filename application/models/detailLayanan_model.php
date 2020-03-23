<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class detailLayanan_model extends CI_Model
{
    private $table = 'detail_layanan'; 

    public $id_detil_l;
    public $id_ukuran_hewan;
    public $id_jenis_hewan;
    public $id_layanan;
    public $total;

    
    public $rule = [
        [
            'field' => 'id_jenis_hewan',
            'label' => 'id_jenis_hewan',
            'rules' => 'required'
        ],

        [
            'field' => 'id_ukuran_hewan',
            'label' => 'id_ukuran_hewan',
            'rules' => 'required'
        ],

        [
            'field' => 'id_layanan',
            'label' => 'id_layanan',
            'rules' => 'required'
        ],
        [
            'field' => 'total',
            'label' => 'total',
            'rules' => 'required'
        ]
    ];
    public function Rules() { return $this->rule; } //Fungsi untuk return nilai rule dimana untuk di cek

    public function getAll()
    {
        // $this->db->select('*');
        // $this->db->from('detail_layanan');
        $this->db->select('h.id_detil_l as "id_detil_l", jh.nama as "jenis_hewan", uh.nama as "ukuran_hewan", c.nama as "layanan", h.total as "total"');
        $this->db->from('detail_layanan h');
        $this->db->join('jenis_hewan jh', 'id_jenis_hewan');
        $this->db->join('ukuran_hewan uh', 'id_ukuran_hewan');
        $this->db->join('layanan c', 'id_layanan');
        return $query = $this->db->get()->result_array(); 
    }

    public function store($request) {   //Fungsi untuk menyimpan data
        //Gunakan $Request untuk mengambil data yang diinputkan oleh user
        $this->id_jenis_hewan = $request->id_jenis_hewan;
        $this->id_ukuran_hewan = $request->id_ukuran_hewan;
        $this->id_layanan = $request->id_layanan;
        $this->total = $request->total; //Mengambil nilai date dari local sesuai format, jadi untuk format ini menggunakan Timestamp
        // $this->created_at = date("Y-m-d"); Yang ini menggunakan format Date
        if($this->db->insert($this->table, $this))
        {
            return ['msg'=>'Berhasil Input Hewan','error'=>false];
        }
            return ['msg'=>'Gagal Input Hewan','error'=>true];
    }

    public function update($request,$id) { //Fungsi untuk update data

        //$this->updated_at = date("Y-m-d H:i:s"); 
        $updateData = ['id_ukuran_hewan' => $request->id_ukuran_hewan, 
                        'id_jenis_hewan' => $request->id_jenis_hewan,
                        'id_layanan' => $request->id_layanan, 
                        'total' => $request->total ]; //Memasukan nilai data update terbaru

        if($this->db->where('id_detil_l',$id)->update($this->table, $updateData)) //Query Update dimana data nya yaitu $updateData
        {
            return ['msg'=>'Berhasil Update Hewan','error'=>false];
        }
            return ['msg'=>'Gagal Update Hewan','error'=>true];
    }
       
    // public function destroy($id) { //Fungsi untuk Soft Delete
 
    //     //$this->deleted_at = date("Y-m-d H:i:s");
    //     //$updateData = ['deleted_at' => $this->deleted_at];

    //     if($this->db->where('id_hewan',$id)->update($this->table, $updateData)) //Query Update karena akan melakukan Soft Delete sehingga jangan delete melainkan update
    //     {
    //         return ['msg'=>'Berhasil Hapus Hewan','error'=>false];
    //     }
    //         return ['msg'=>'Gagal Hapus Hewan','error'=>true];
    // }

    public function getById($id)
    {
        return $this->db->get_where($this->_table, ["id_hewan" => $id])->row();
    }

    public function destroy($id)  //Fungsi Untuk Delete
    {
        if (empty($this->db->select('*')->where(array('id_detil_l' => $id))->get($this->table)->row())) return ['msg' => 'Id tidak ditemukan', 'error' => true];

        if ($this->db->delete($this->table, array('id_detil_l' => $id))) {
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }
   
}
?>