<?php
defined('BASEPATH') or exit('No direct script access allowed');
class LoginModel extends CI_Model
{
    private $table = 'pegawai';
    public $id_pegawai;

    public $username;
    public $password;
    public $rule = [
        [
            'field' => 'username',
            'label' => 'username',
            'rules' => 'required',
        ],
    ];

    public function Rules()
    {
        return $this->rule;
    }

    public function getAll()
    {
        return
            $this->db->get('pegawai')->result();
    }

    public function store($request)
    {
        $this->username = $request->username;
        $this->password = $request->password;
        if ($this->db->insert($this->table, $this)) {
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function update($request, $id_pegawai)
    {
        $updateData = ['username' => $request->username];
        if ($this->db->where('id_pegawai', $id_pegawai)->update($this->table, $updateData)) {
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function destroy($id_pegawai)
    {
        if (empty($this->db->select('*')->where(array('id_pegawai' => $id_pegawai))->get($this->table)->row())) return ['msg' => 'Id tidak ditemukan', 'error' => true];

        if ($this->db->delete($this->table, array('id_pegawai' => $id_pegawai))) {
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function verifyUser($request)
    {
        $pegawai = $this->db->select('*')->where(array('username' => $request->username, 'password' => $request->password))->get($this->table)->row_array();
        if(!empty($pegawai) ){
            return $pegawai;          
        }else{
            return false;
        }
    }
}
?>