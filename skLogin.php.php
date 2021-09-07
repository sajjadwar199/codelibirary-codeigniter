<?php 
class skLogin extends CI_Controller{


     /***************************** setting start  *****************************/
     /** class by sajjad kareem */
    //table users in database 
    private $table = 'users';
    //validation set
    private  $validation = array(
        array(
            'field' => 'username',
            'label' => ' اسم المستخدم ',
            'rules' => 'required',
            'errors' => array(
                'required' => 'الرجاء ملء حقل %s.',
            ),
        ),
        array(
            'field' => 'password',
            'label' => ' كلمة المرور ',
            'rules' => 'required',
            'errors' => array(
                'required' => 'الرجاء ملء حقل %s.',
            ),
        )
    );
    //set query for selecting login
    private function setQuerylogin()
    {
        // 1- posts value from form 
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        return $this->db->where(['username' => $username, 'password' => $password])->from($this->table);
    } 
    //set login with session data
    public function setLoginSession($result){
     //البيانات التي تحفظ في ال session
        foreach ($result as  $userinfo) {
            $newdata = array(
                "id" =>  $userinfo->id,
                "username" =>  $userinfo->username,
                "date" => $userinfo->date,
                "add_by" => $userinfo->add_by
                
            );
        }
        return $newdata;
    }
    //اسم صفحة التحويل بعد تسجيل الدخول
    public $mainpageAfterLogin = "home";

    public function index($page = "login")
    {
        $data['title'] = " تسجيل دخول  ";
        $data['pageinfo2'] = " ";
        $data['pageinfo3'] = " ";
        $this->load->view('pages/' . $page, $data);
    }
    /***************************** setting end  *****************************/
    public function  __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    //showing page of login 
    public function login()
    {
        //2-check this login database 
        $this->form_validation->set_rules($this->validation);
        if ($this->form_validation->run() == true) {
            $check_login = $this->setQuerylogin()->count_all_results();
            //3-if login then redirect main dashbord page else back page login 
            if ($check_login > 0) {
                // if login   and set session and get user information
                //check data in databse for get data 
                $query = $this->setQuerylogin()->get();
                $result = $query->result();
                $newdata= $this->setLoginSession($result);
                    $this->session->set_userdata($newdata);
                redirect($this->mainpageAfterLogin);
            } else {
                //if not login 
                $this->session->set_flashdata('error', 'هناك خطأ في اسم المستخدم او كلمة المرور');
                $this->index();
            }
        } else {
            $this->index();
        }
    }
    public function logout()
    {
        $this->session->sess_destroy();
        $this->index();
    }
}

?>