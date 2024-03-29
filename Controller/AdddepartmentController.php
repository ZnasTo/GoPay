<?php
class AdddepartmentController extends Controller
{
    public function execute($parameters) {
        if($_SESSION["prihlasen"] == true){
            $this->view = "adddepartment";
            if(isset($_POST["submit"])){
                $nazev = $_POST["nazev"];
                $url = $_POST["url"];
                $notification_url = $_POST["notification_url"];
                $api_token = $_POST["api_token"];
                Db::query(
                    "INSERT INTO oddeleni (nazev, url, notification_url, api_token) VALUES (?, ?, ?, ?)", 
                    array($nazev, 
                        $url, 
                        $notification_url, 
                        $api_token
                    )
                );
                $this->redirect("department");
            }
        }
        else{
            $this->redirect("login");
        }
    }
}