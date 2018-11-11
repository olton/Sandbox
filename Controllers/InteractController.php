<?php


namespace Controllers;


use Classes\Controller;

class InteractController extends Controller {
    public function PostForm(){
        global $POST;
        $result = isset($POST['result']) ? $POST['result'] : true;
        $this->ReturnJSON($result, $result == false ? "Error" : "OK", $POST);
    }

    public function GetForm(){
        global $GET;
        $result = isset($POST['result']) ? $GET['result'] : true;
        $this->ReturnJSON($result, $result == false ? "Error" : "OK", $GET);
    }

    public function TableData(){
        global $REQUEST;
        $table = "table.json";
        if (isset($REQUEST['wide'])) {
            $table = "table-wide.json";
        }
        $table_data = file_get_contents(SANDBOX_PATH . "data/table" . DSP . $table);
        $this->ReturnContent($table_data);
    }
}