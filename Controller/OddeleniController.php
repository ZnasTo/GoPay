<?php
class oddeleniController extends Controller
{
    public function execute($parameters) {
        if($_SESSION["prihlasen"] == true){
            $this->view = "oddeleni";
            $this->data["oddeleni"] = Db::queryAll("SELECT * FROM oddeleni");
        }
        else{
            $this->redirect("login");
        }
    }
    
}