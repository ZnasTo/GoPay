<?php
class EditdepartmentController extends Controller
{
    public function execute($parameters) {
        if($_SESSION["prihlasen"] == true){
            if(isset($_GET["id"])){
                $id = $_GET["id"];
                $this->data["oddeleni"] = Db::queryOne("SELECT * FROM oddeleni WHERE nazev LIKE '$id'");
                $this->view = "editdepartment";
                if(isset($_POST["submit"])){
                    $nazev = $_POST["nazev"];
                    $url = $_POST["url"];
                    $notification_url = $_POST["notification_url"];
                    $api_token = $_POST["api_token"];
                    Db::query("UPDATE oddeleni SET nazev = '$nazev', url = '$url', notification_url = '$notification_url', api_token = '$api_token' WHERE nazev LIKE '$id'");
                    $this->redirect("oddeleni");
                }
            }

            
        }
        else{
            $this->redirect("login");
        }
    }    
}