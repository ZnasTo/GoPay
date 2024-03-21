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
                Db::query("INSERT INTO oddeleni (nazev, url, notification_url) VALUES ('$nazev', '$url', '$notification_url')");
                $this->redirect("oddeleni");
            }
        }
        else{
            $this->redirect("login");
        }
    }
    
}