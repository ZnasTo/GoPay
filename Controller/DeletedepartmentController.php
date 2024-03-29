<?php
class DeletedepartmentController extends Controller
{
    public function execute($parameters) {
        if($_SESSION["prihlasen"] == true){
            if(isset($_GET["id"])){
                $id = $_GET["id"];
                Db::query("DELETE FROM oddeleni WHERE nazev LIKE ?", array($id));
            }
            $this->redirect("department");
        }
        else{
            $this->redirect("login");
        }
    }
    
}