<?php 
// Třída pro čtení dat z databáze
class AdministrationController extends Controller{
    public function execute($parameters){
        // Kontrola přihlášení uživatele
        if($_SESSION["prihlasen"] == true){

            // Dotaz pro ziskani dat z databaze
            $transaction = Db::queryAll(" SELECT *
            FROM transakce;
            ");

            // Odstranění záznamu
            if (isset($_GET["odstranit"])) {
                $transactionId = $_GET["odstranit"];
                Db::delete("transakce","id_transakce",$transactionId);; 
                $this->redirect("administration");
            }

            $this->data["transakce"] = $transaction;
            $this->view = "administration";

            // Přesměrování na výpis requestu
            if (isset($_GET["request"])) {
                $transactionId = $_GET["request"];
                $this->redirect("showrequest?id_transakce=$transactionId");
            }
            
        }
        else{
            // Přesměrování na login
            $this->redirect("login");
        }
        
    }


}
