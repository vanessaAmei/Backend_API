<?php
use Restserver \Libraries\REST_Controller;
Class User extends REST_Controller
{
    public function __construct()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Authorization, Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('UserModel');
        $this->load->library('form_validation');
        $this->load->library('email');
        $this->load->helper(['jwt', 'Authorization']);
    }

    public function index_get()
    {
        // $data = $this->verify_request();
        // // Send the return data as reponse
        // $status = parent::HTTP_OK;
        // $response = ['status' => $status, 'data' => $data];
        // $this->response($response, $status);
        return $this->returnData($this->db->get('users')->result(), false);
    }

    public function index_post($id = null)
    {
        $validation = $this->form_validation;
        $rule = $this->UserModel->rules();
        if ($id == null) {
            array_push(
                $rule,
                [
                    'field' => 'username',
                    'label' => 'username',
                    'rules' => 'required'
                ],
                [
                    'field' => 'password',
                    'label' => 'password',
                    'rules' => 'required'
                ],
                [
                    'field' => 'email',
                    'label' => 'email',
                    'rules' => 'required|valid_email|is_unique[users.email]'
                ],
                [
                    'field' => 'phone_number',
                    'label' => 'phone_number',
                    'rules' => 'required|numeric'
                ],
                [
                    'field' => 'address',
                    'label' => 'address',
                    'rules' => 'required'
                ],
            );
        } else {
            array_push(
                $rule,
                [
                    'field' => 'username',
                    'label' => 'username',
                    'rules' => 'required'
                ],
                [
                    'field' => 'email',
                    'label' => 'email',
                    'rules' => 'required|valid_email'
                ]
            );
        }
        $validation->set_rules($rule);
        if (!$validation->run()) {
            return $this->returnData($this->form_validation->error_array(), true);
        }
        $user = new UserData();
        $user->username = $this->post('username');
        $user->email = $this->post('email');
        $user->password = $this->post('password');
        $user->address = $this->post('address');
        $user->phone_number = $this->post('phone_number');
        $user->role = "User";
        if ($id == null) {
            $response = $this->UserModel->store($user);
            $id_baru = $this->UserModel->get_id($user);
            $this->send_email_verification($user->email,$id_baru);
        } else {
            $response = $this->UserModel->update($user, $id);
        }
        return $this->returnData($response['msg'], $response['error']);
    }


    public function userLogin_post($id = null)
	{
        $user = new UserData();
        $user->email = $this->post('email');
        $user->password = $this->post('password');
        if ($id == null)
        {
            $response = $this->UserModel->response_login($user);
        }
        else{
            // $response = $this->UserModel->verify_email($id);
        }
		return $this->returnData($response['msg'], $response['error']);
    }

    public function info_post()
    {
        $user = new UserData();
        $user->email = $this->post('email');
        $response = $this->UserModel->get_info_by_email($user);
        // return $this->returnData($response->result(),false);
        return $this->returnDataAll($response['id'], $response['username'], $response['email'], $response['password'], $response['address'], $response['phone_number'], $response['role']);
    }

    public function verification_get($id)
    {
        $response = $this->UserModel->verify_email($id);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function index_delete($id = null)
    {
        if ($id == null) {
            return $this->returnData('Parameter Id Tidak Ditemukan', true);
        }
        $response = $this->UserModel->destroy($id);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function returnDataAll($id,$username,$email,$password,$address,$phone_number,$role)
    {
        $response['id'] = $id;
        $response['username'] = $username;
        $response['email'] = $email;
        $response['password'] = $password;
        $response['address'] = $address;
        $response['phone_number'] = $phone_number;
        $response['role'] = $role;
        return $this->response($response);
    }

    public function returnData($msg, $error)
    {
        $response['error'] = $error;
        $response['message'] = $msg;
        return $this->response($response);
    }

    public function send_email_verification($email,$id_baru)
    {
        $config = [
            'mailtype'  => 'html',
            'charset'   => 'utf-8',
            'protocol'  => 'smtp',
            'smtp_host' => 'ssl://smtp.gmail.com',
            'smtp_user' => 'kevinseptianto75@gmail.com',    
            'smtp_pass' => 'Kevin124',      
            'smtp_port' => 465,
            'crlf'      => "\r\n",
            'newline'   => "\r\n"
        ];

        $this->email->initialize($config);
        $this->email->set_mailtype("html");
        $this->email->set_newline("\r\n");  

        //email content
         $this->load->library('email');
         $this->email->from('kevinseptianto75@gmail.com');
         $this->email->to($email); 
         $message = "http://localhost:8081/CITugasBesar/index.php/user/verification/" . $id_baru;//URL Verification
         $this->email->message($message);
         $this->email->subject('Verification Email');
         
         if ($this->email->send()) {
             $text = "Sukses kirim email! Cek Email Anda";
         } else {
             $text = "Gagal kirim Email cek lagi EMAIL dan PW SMTP bos!";
         }
    }

    private function verify_request()
    {
        // Get all the headers
        $headers = $this->input->request_headers();
        // Extract the token
        if(isset($headers['Authorization'])){
            $header =  $headers['Authorization'];
        }else
        {
            $status = parent::HTTP_UNAUTHORIZED;
            $response = ['status' => $status, 'msg' => 'Unauthorized Access!'];
            return $response;
        }
        
        $token = explode(" ", $header)[1];
        // Use try-catch
        // JWT library throws exception if the token is not valid
        try {
            // Validate the token
            // Successfull validation will return the decoded user data else returns false
            $data = AUTHORIZATION::validateToken($token);

            if ($data === false) {
                $status = parent::HTTP_UNAUTHORIZED;
                $response = ['status' => $status, 'msg' => 'Unauthorized Access!'];
            } else {
                $response = ['status' => 200, 'msg' => $data];
            }
            return $response;
        } catch (Exception $e) {
            // Token is invalid
            // Send the unathorized access message
            $status = parent::HTTP_UNAUTHORIZED;
            $response = ['status' => $status, 'msg' => 'Unauthorized Access! '];
            //$this->response($response, $status);
            return $response;
        }
    }
}
class UserData
{
    public $username;
    public $password;
    public $email;
    public $address;
    public $phone_number;
}