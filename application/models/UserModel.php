<?php
defined('BASEPATH') or exit('No direct script access allowed');
class UserModel extends CI_Model
{
    private $table = 'users';
    public $id;
    public $username;
    public $email;
    public $password;
    public $address;
    public $phone_number;
    public $role;
    public $rule = [
        [
            'field' => 'username',
            'label' => 'username',
            'rules' => 'required',
        ],
        [
            'field' => 'email',
            'label' => 'email',
            'rules' => 'required|valid_email|is_unique[users.email]',
        ],
    ];

    public function Rules()
    {
        return $this->rule;
    }

    public function getAll()
    {
        return
            $this->db->get('data_mahasiswa')->result();
    }

    public function verify_email($id)
    {
        $verif = 'verified';
        $updateData = ['verification' => $verif];
        if ($this->db->where('id', $id)->update($this->table, $updateData)) {
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function response_login($request)
    {   
        $user = $this->db->select('*')->where(array('email' => $request->email))->get($this->table)->row_array();
        if (!empty($user) && password_verify($request->password, $user['password'])) {
            if($user['verification'] == 'verified')
            {
                return ['msg' => 'Berhasil', 'error' => false];
            }
            else{
                return ['msg' => 'Email belum dikonfirmasi, Silahkan cek email', 'error' => true];
            }
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function get_info_by_email($request)
    {
        $user = $this->db->select('*')->where(array('email' => $request->email))->get($this->table)->row_array();
        // return $user;
        return [ 'id' => $user['id'],'username' => $user['username'],'email' => $user['email'], 'password' => $user['password'], 'address' => $user['address'], 'phone_number' => $user['phone_number'], 'role' => $user['role']];
    }

    public function get_id($request)
    {
        $user = $this->db->select('*')->where(array('email' => $request->email))->get($this->table)->row_array();
        if (!empty($user) && password_verify($request->password, $user['password'])) {
            return $user['id'];
        } else {
            return ['msg' => 'Gagal', 'error' => true];
        }
    }

    public function store($request)
    {
        $this->username = $request->username;
        $this->email = $request->email;
        $this->password = password_hash($request->password, PASSWORD_BCRYPT);
        $this->address = $request->address;
        $this->phone_number = $request->phone_number;
        $this->role = $request->role;


        if ($this->db->insert($this->table, $this)) {
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function update($request, $id)
    {
        $updateData = ['email' => $request->email, 'username' => $request->username, 'address' => $request->address, 'phone_number' => $request->phone_number];
        if ($this->db->where('id', $id)->update($this->table, $updateData)) {
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function destroy($id)
    {
        if (empty($this->db->select('*')->where(array('id' => $id))->get($this->table)->row())) return ['msg' => 'Id tidak ditemukan', 'error' => true];

        if ($this->db->delete($this->table, array('id' => $id))) {
            return ['msg' => 'Berhasil', 'error' => false];
        }
        return ['msg' => 'Gagal', 'error' => true];
    }

    public function verifyUser($request)
    {
        $user = $this->db->select('*')->where(array('email' => $request->email))->get($this->table)->row_array();
        if (!empty($user) && password_verify($request->password, $user['password'])) {
            return $user;
        } else {
            return ['msg' => 'Gagal', 'error' => true];
        }
    }

    public function verify($request){
        $user = $this->db->select('*')->where(array('email' => $request->email))->get($this->table)->row_array();
        if(!empty($user) && password_verify($request->password, $user['password'])){
            return $user;
        }else{
            return false;
        }
    }
}
