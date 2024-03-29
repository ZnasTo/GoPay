<?php
class DepartmentController extends Controller
{
    public function execute($parameters) {
        if($_SESSION["prihlasen"] == true){
            $this->view = "department";
            $this->data["oddeleni"] = Db::queryAll("SELECT * FROM oddeleni");

            // Přesměrování na výpis api_tokenu
            if (isset($_GET["api_token"])) {
                $oddeleni = $_GET["api_token"];
                $this->redirect("showtoken?department=$oddeleni");
            }
        }
        else{
            $this->redirect("login");
        }
    }
    
}