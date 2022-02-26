<?php
/* =========================== مثال عن طريقة الأستدعاء ======================================= 

1-نستدعي مكتبة spreadshet من خلال     
    composer require phpoffice/phpspreadsheet
*/
 

/* defined('BASEPATH') or exit('No direct script access allowed');
require(APPPATH . 'libraries/skimportExcel.php');
class importExcel extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        $this->load->view("dashbord_inc/header.php");
        $this->load->view("pages/importExcel.php");
        $this->load->view("dashbord_inc/footer.php");
    }
     public function CheckExcel()
    {
        new SkimportExcel($this->db,"select_excel", "salery", array(
            'a',
            'b',
            'c',
            'd',
            'e',
            'f',
            'g',
            'h',
            'i',
            'j',
            'k',
        ));
    }
};
  =========================== مثال عن طريقة الأستدعاء =======================================  

 */
defined('BASEPATH') or exit('No direct script access allowed');
 use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
class SkimportExcel    
{
    //class by  sajjadkareem create at 2022/2/11 wt 11:58 pm
    /** ----------------------setting-start---------------------- */
     protected $db;
    //الحقول في قاعدة البيانات 
    private  $colsName = array(
        'name',
        'age',
        'c',
        'd',
        'e',
        'f'
    );
    //أسم الجدول في قاعدة البيانات
    private   $tableDatabase = "salery";
    /* قائمة الرسائل */
    public const  MESSAGES = [
        'EMPTYFILE' => "من فضلك قم بأختيار ملف",
        'EXTENTION' => " فقط هذه الصيغ مسموحة  csv,excel(xlsx,xls)",
        'BOOTSTRAP-ERROR-START' => '<div class="alert alert-danger">',
        'BOOTSTRAP-ERROR-END' => '</div>',
        'BOOTSTRAP-SUCCESS-START' => '<h3 class="alert alert-success">',
        'BOOTSTRAP-SUCCESS-END' => '</h3>',
        'ROWS-COUNT-SUCCESS' => "تم بنجاح أستيراد ملف الأكسل بصفوف عددها  :  "
    ];
    //post input name 
    public $fileInputName = "select_excel";
    /** ----------------------setting-end---------------------- */
    /** 
     * insertToMysql
     *
     * @param  mixed $sheetData
     * @return void
     */
    public function  insertToMysql($sheetData, $spreadsheet)
    {
        //
        $colsnumber = count($sheetData[0]);
        //أستخراج الحقول من ملف الأكسل
        $writer = IOFactory::createWriter($spreadsheet, 'Html');
        //الاضافة الى قاعدة البيانات 
        // لجمع البيانات على شكل array 
        $getData = array();
        //حساب عدد الصفوف
        $rowsCount = 0;
        //أستيراد الصفوف لقاعدة البيانات
        for ($i = 0; $i < count($sheetData); $i++) {
            for ($j = 0; $j < count($this->colsName); $j++) {
                @$col = @$sheetData[$i][$j];
                //فحص الحقل ان كان فارغ ام لا  
                if (@$col == NULL) {
                    @$col = " ";
                } else {
                    @$col = $col;
                }
                $getData[$this->colsName[$j]] = $col;
            }
            //insert to mysql part
            $this->db->insert($this->tableDatabase, $getData);
            //print_r($getData);
            $rowsCount++;
        }
        // echo  implode(",", $getData);  
        //طباعة رسالة لعدد الصفوف المستوردة
        echo  $this->CreateMessage(
            self::MESSAGES['BOOTSTRAP-SUCCESS-START'],
            self::MESSAGES['ROWS-COUNT-SUCCESS'] . $rowsCount,
            self::MESSAGES['BOOTSTRAP-SUCCESS-END']
        );
        //لطباعة جدول الأكسل المستورد بعد  الأستيراد 
        echo "<div style='overflow:scroll;height:350px;'> ";
        $writer->save('php://output');
        echo "</div>";
    }
    public function __construct($CI_database_class,$fileInputName, $TableName, $colsName = array())
    {
        $this->fileInputName = $fileInputName;
        $this->tableDatabase = $TableName;
        $this->colsName = $colsName;
        $this->db=$CI_database_class;
        $this->CheckExcel();
    }
    //=============استلام الملف من المستخدم وفحص الصيغة=================    
    /**
     * CheckExcel
     *
     * @return void
     */
    public function CheckExcel()
    {
        $Spreadsheet = new Spreadsheet();
        // انواع الملفات
        $file_mimes = array(
            'text/x-comma-separated-values',
            'text/comma-separated-values', 'application/octet-stream',
            'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv',
            'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel',
            'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        );
        //===================validation=================== 
        if (empty(($_FILES[$this->fileInputName]['name']))) {
            //if file empty 
            echo  $this->CreateMessage(
                self::MESSAGES['BOOTSTRAP-ERROR-START'],
                self::MESSAGES['EMPTYFILE'],
                self::MESSAGES['BOOTSTRAP-ERROR-END']
            );
        } elseif (!in_array($_FILES[$this->fileInputName]['type'], $file_mimes)) {
            //if file not excel extention 
            echo  $this->CreateMessage(
                self::MESSAGES['BOOTSTRAP-ERROR-START'],
                self::MESSAGES['EXTENTION'],
                self::MESSAGES['BOOTSTRAP-ERROR-END']
            );
        }
        //================================================
        //===============checkfile========================
        if (isset($_FILES[$this->fileInputName]['name']) && in_array($_FILES[$this->fileInputName]['type'], $file_mimes)) {
            $arr_file = explode('.', $_FILES[$this->fileInputName]['name']);
            $extension = end($arr_file);
            if ('csv' == $extension) {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            } elseif ('xls' == $extension) {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            } else {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }
            //load file excel 
            $spreadsheet = $reader->load($_FILES[$this->fileInputName]['tmp_name']);
            //**************/
            $sheetData = $spreadsheet->getActiveSheet()->toArray();
            // echo "<pre>";
            //            print_r($sheetData);
            if (!empty(($_FILES[$this->fileInputName]['name'])) and in_array($_FILES[$this->fileInputName]['type'], $file_mimes)) {
                //ok checked excel 
                $this->importExcelFileToMysql($reader, $sheetData);
            }
        }
        //==================================================
    }
    //=========== تحليل ملف الاكسل والقيام بعملية الاضافة الى قاعدة البيانات================    
    /**
     * importExcelFileToMysql
     *
     * @param  mixed $reader
     * @param  mixed $sheetData
     * @return void
     */
    public function importExcelFileToMysql($reader, $sheetData)
    {        /*  تحميل ملف الأكسل */
        $spreadsheet = $reader->load($_FILES[$this->fileInputName]['tmp_name']);
        /* تجزئة ملف الأكسل الى حقول  */
        if (!empty($sheetData)) {
            $this->insertToMysql($sheetData, $spreadsheet);
        }
    }
    //===============لتوليد الرسائل على حسب النوع =========================    
    /**
     * CreateMessage
     *
     * @param  mixed $messageStartBootartap
     * @param  mixed $message
     * @param  mixed $messageEndBootartap
     * @return void
     */
    public function CreateMessage($messageStartBootartap, $message, $messageEndBootartap)
    {
        return  $messageStartBootartap . $message . $messageEndBootartap;
    }
}
