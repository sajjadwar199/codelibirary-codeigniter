<?php
defined('BASEPATH') or exit('No direct script access allowed');
class skCrudController extends CI_Controller
{
        /******************************** setting start ************************************* */
        /**
         * 
         *  controller مكتبة عمل بسيطة  ل  crud تمت برمجتها بواسطة سجاد عبد الكريم  
         *  create in 2021/9/7 at 4:50am
         */
        /**
         * idName اسم المعرف في قاعدة البيانات
         *
         * @var string
         */
        private $idName = 'id';
        /**
         * datapassToViewEditName   اسم متغير الداتا الذي يعبر في صفحة لتعديل
         *
         * @var string
         */
        private $datapassToViewEditName = "brands";
        /**
         * modelName أسم كلاس  المودل 
         *
         * @var string
         */
        private $modelClassName = 'brandsModel';
        /**
         * passingDataToView  view المتغيرات التي تعبر الى صفحة
         *
         * @var array
         */
        private  $passingDataToView = [
                "index" => [
                        "title" => "الماركات",
                        "pageinfo1" => "أدارة الماركات",
                        "pageinfo2" => "الماركات",
                        "pageinfo3" => " ",
                ],
                "create" => [
                        "title" => "الماركات",
                        "pageinfo1" => "أدارة الماركات",
                        "pageinfo2" => "الماركات",
                        "pageinfo3" => "اضافة ماركة جديدة"
                ],
                "edit" => [
                        "title" => "الماركات",
                        "pageinfo1" => "أدارة الماركات",
                        "pageinfo2" => "الماركات",
                        "pageinfo3" => "تعديل الماركة  ",
                ],
        ];
        /**
         * headerPageurl  header رابط صفحة   
         *
         * @var string
         */
        private $headerPageurl = 'template/header';
        /**
         * footerPageurl   footer رابط صفحة  
         *
         * @var string
         */
        private $footerPageurl = 'template/footer';
        /**
         * pagesCrudUrls  crud أسماء صفحات 
         *
         * @var array
         */
        private $pagesCrudUrls = [
                "index" => "pages/brands/brands",
                "create" => "pages/brands/create_brands",
                "edit" => "pages/brands/updateformbrand"
        ];
        /**
         * set_csrf لحماية المدخلات
         *
         * @return void
         */
        private  function set_csrf()
        {
                $csrf = array(
                        'brand_name' => $this->security->get_csrf_token_name(),
                        'status' => $this->security->get_csrf_token_name(),
                        'hash' => $this->security->get_csrf_hash(),
                );
                return $csrf;
        }
        private   $validation = array(
                array(
                        'field' => 'brand_name',
                        'label' => 'اسم الماركة ',
                        'rules' => 'required',
                        'errors' => array(
                                'required' => 'الرجاء ملء حقل %s.',
                        ),
                ),
                array(
                        'field' => 'status',
                        'label' => ' الحالة ',
                        'rules' => 'required',
                        'errors' => array(
                                'required' => 'الرجاء اختيار حالة الماركة',
                        ),
                )
        );

        /**
         * set_posts_input_insert
         *
         * @return void
         */
        public function set_posts_input_insert()
        {
                //validation 
                $brand_name = $this->input->post('brand_name');
                $status = $this->input->post('status');
                if ($this->input->post('status') == 1) {
                        $status = '<span class="badge bg-success">فعال</span>';
                } else {
                        if ($this->input->post('status') == 0) {
                                $status = '<span class="badge bg-danger">غير فعال</span>';
                        }
                }
                $date = date('Y/m/d g:i:s A');
                $data = array(
                        'brand_name' => $brand_name,
                        'status' => $status,
                        'date' => $date
                );
                return $data;
        }
        /**
         * set_posts_input_update
         *
         * @return void
         */
        public function set_posts_input_update()
        {
                $status = $this->input->post('status');
                if ($status == 1) {
                        $status = '<span class="badge bg-success">فعال</span>';
                } else {
                        if ($status == 0) {
                                $status = '<span class="badge bg-danger">غير فعال</span>';
                        }
                }
                $data = array(
                        'id' => $this->input->post('id'),
                        'status' => $status,
                        'brand_name' => $this->input->post('brand_name')
                );
                return $data;
        }
        /**
         * set_show_data_ajax دالة تقوم بجلب المعلومات لصفحة الأجاكس
         *
         * @return void
         */
        private  function set_show_data_ajax()
        {
                $model = new $this->modelClassName;
                $model_data = $model->get_data();
                $data = array();
                foreach ($model_data as $r) {
                        $data[] = array(
                                $r->brand_name,
                                $r->status,
                                $r->date,
                                "<a class='btn btn-success'" . "href=" .  base_url() . get_class($this) . "/edit/" . $r->id . "><i class='fas fa-edit '></i></a>",
                                "<a  class='btn btn-danger'" . "href=" .  base_url() . get_class($this) . "/delete/" . $r->id . "><i class='fas fa-trash-alt '></i></a>",
                        );
                }
                return $data;
        }

        /**
         * message_success رسائل الصح
         *
         * @var array
         */
        private $message_success = ["update" => "تم تعديل الصنف بنجاح", "insert" => "تم اضافة الماركة بنجاح"];
        /******************************** setting end ************************************* *********************/

        public  function __construct()
        {
                parent::__construct();
                // لأستدعاء المودل 
                $this->load->model($this->modelClassName);
        }
        //لعرض البيانات وتمريرها الى الصفحة الرئيسية         
        /**
         * index
         *
         * @return void
         */
        public function index()
        {
                $data = $this->passingDataToView['index'];
                $this->load->view($this->headerPageurl, $data);
                $this->load->view($this->pagesCrudUrls['index'], $data);
                $this->load->view($this->footerPageurl, $data);
        }
        //للاضافة
        /**
         * insert
         *
         * @return void
         */
        public function insert()
        {
                $this->set_csrf();
                $this->form_validation->set_rules($this->validation);
                $Model = new $this->modelClassName;
                if ($this->form_validation->run() == true) {
                        if ($Model->insert($this->set_posts_input_insert()) == true) {
                                $this->session->set_flashdata('success', $this->message_success['insert']);
                                echo $this->session->flashdata('success');
                                redirect(get_class($this) . "/index");
                        };
                } else {
                        $this->create();
                }
        }
        //لانشاء فورم الاضافة 
        public function  create()
        {
                $data = $this->passingDataToView['create'];
                $this->load->view($this->headerPageurl, $data);
                $this->load->view($this->pagesCrudUrls['create'], $data);
                $this->load->view($this->footerPageurl, $data);
        }
        //لملأ فورم التعديل         
        /**
         * edit
         *
         * @param  mixed $id
         * @return void
         */
        public function edit($id)
        {
                //فحص اذا كان العنصر غير موجود للتحديث
                $Model = new  $this->modelClassName;
                $check = $Model->get_count_where($id);
                if ($check >= 1) {
                        $data = $this->passingDataToView['edit'];
                        $data["$this->datapassToViewEditName"] = $Model->get_where($id);
                        $this->load->view($this->headerPageurl, $data);
                        $this->load->view($this->pagesCrudUrls['edit'], $data);
                        $this->load->view($this->footerPageurl, $data);
                } else {
                        show_404();
                }
        }
        //للتعديل        
        /**
         * update
         *
         * @return void
         */
        public function update()
        {
                $Model = new $this->modelClassName;
                $Model->update($this->set_posts_input_update());
                $this->form_validation->set_rules($this->validation);
                if ($this->form_validation->run() == true) {
                        if ($Model->update($this->set_posts_input_update()) == true) {
                                $this->session->set_flashdata('success', $this->message_success['update']);
                                echo $this->session->flashdata('success');
                                redirect(get_class($this) . '/index');
                        }
                } else {
                        $data = $this->passingDataToView['edit'];
                        $data["$this->datapassToViewEditName"] = $Model->get_where($this->set_posts_input_update()[$this->idName]);
                        $this->load->view($this->headerPageurl, $data);
                        $this->load->view($this->pagesCrudUrls['edit'], $data);
                        $this->load->view($this->footerPageurl, $data);
                }
        }
        //للحذف        
        /**
         * delete
         *
         * @param  mixed $id
         * @return void
         */
        public function delete($id)
        {
                $model = new $this->modelClassName;
                $check = $model->get_count_where($id);
                if ($check >= 1) {
                        $model->delete($id);
                } else {
                        show_404();
                }
        }
        //لعرض البيانات على شكل  اجاكس        
        /**
         * brandsList
         *
         * @return void
         */
        public function List()
        {
                // Datatables Variables
                $model = new $this->modelClassName;
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
        }
}
