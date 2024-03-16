<?php
class AdddepartmentController extends Controller
{
    public function execute($parameters) {
        if($_SESSION["prihlasen"] == true){
            $this->view = "adddepartment";
            if(isset($_POST["submit"])){
                $nazev = $_POST["nazev"];
                $url = $_POST["url"];
                Db::query("INSERT INTO oddeleni (nazev, url) VALUES ('$nazev', '$url')");
                $this->redirect("oddeleni");
            }
        }
        else{
            $this->redirect("login");
        }
    }
    
}