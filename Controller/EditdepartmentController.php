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
                    Db::query("UPDATE oddeleni SET nazev = '$nazev', url = '$url' WHERE nazev LIKE '$id'");
                    $this->redirect("oddeleni");
                }
            }

            
        }
        else{
            $this->redirect("login");
        }
    }    
}