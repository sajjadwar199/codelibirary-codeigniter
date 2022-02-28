<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 *    create crud with modalBootstrap and ajax
 * @package skLibiraris
 * @author sajjadkareem 
 * @category modalAjaxCrud
 * 
 * 
 */
class skCrudModalAjax  extends CI_Controller
{
     public   $ModelClassName = "manageUsersModel";
    public   $validation = array(
        array(
            'field' => 'username',
            'label' => 'أسم المستخدم ',
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
        ),
        array(
            'field' => 'validity',
            'label' => ' الصلاحية ',
            'rules' => 'required',
            'errors' => array(
                'required' => 'الرجاء أختيار   %s.',
            ),
        )
    );
    public function __construct()
    {
        parent::__construct();
        // import model 
        $this->load->model($this->ModelClassName, "ModelName");
        $this->load->library('form_validation');
    }
    public function set_show_data($r)
    {
        return  array(
            $r->username,
            $r->password,
            $r->validity,
            "<a      class='btn btn-success update_btn' id=" . $r->id .  ">تعديل</a>",
            "<a  class='btn btn-danger delete_btn' id=" .  $r->id . ">حذف</a>",
        );
    }





    /* setting end *******************************************8 */
    public function index()
    {
        $this->load->view("dashbord_inc/header.php");
        $this->load->view("pages/importExcel.php");
        $this->load->view("dashbord_inc/footer.php");
    }
    /**
     * insert لأضافة البيانات 
     *
     * @return void
     */
    public function insert()
    {
        // set data
        $data = array(
            "username" => $this->input->post("username"),
            "password" => $this->input->post("password"),
            "validity" => $this->input->post("validity")
        );
        //validation 
        $this->form_validation->set_rules($this->validation);
        if ($this->form_validation->run() != false) {
            // insert 
            $res = $this->ModelName->insert($data);
            json_encode($res);
        } else {
            echo json_encode(['success' => false]);
        }
    }
    /**
     * edit    لعرض العنصر المراد تعديله
     *
     * @param  mixed $id
     * @return void
     * 
     * 
     * 
     */
    public function edit()
    {
        $id = $this->input->post("id");
        $data = $this->ModelName->get_where($id);
        echo json_encode($data);
    }
    /**
     * update  لتعديل البيانات
     *
     * @return void
     */
    public function update()
    {
        $data = array(
            "id" => $this->input->post("id"),
            "username" => $this->input->post("username"),
            "password" => $this->input->post("password"),
            "validity" => $this->input->post("validity")
        );
        $res = $this->ModelName->update($data);
        json_encode($res);
        echo $this->input->post("id");
    }
    /**
     * delete لحذف البيانات
     *
     * @param  mixed $id
     * @return void
     */
    public function delete()
    { 
        $id=$this->input->post("id");
        $delet = $this->ModelName->delete($id);
        echo json_encode($delet);
    }
    /**
     * show لعرض البيانات 
     *
     * @param  mixed $id
     * @return void
     */
    public function show($id)
    {
        $show =  $this->ModelName->get_where($id);
        json_encode($show);
    }
    public  function set_show_data_ajax()
    {
        $model = new $this->ModelName;
        $model_data = $model->get_data();
        $data = array();
        foreach ($model_data as $r) {
            $data[] = $this->set_show_data($r);
        }
        return $data;
    }
    /**
     * List لعرض البيانات وتحويلها الى json
     *
     * @return void
     */
    public function List()
    {
        // Datatables Variables
        $model = new $this->ModelName;
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
        $output = array(
            "draw" => $draw,
            "recordsTotal" => $model->get_count(),
            "recordsFiltered" => $model->get_count(),
            "data" => $this->set_show_data_ajax()
        );
        echo json_encode($output);
        exit();
        $data = $this->ModelName->get_data();
        json_encode($data);
    }
}
