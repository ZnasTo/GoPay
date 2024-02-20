<?php 
// Třída pro čtení dat z databáze
class AdministrationController extends Controller{
    public function execute($parameters){
        // Kontrola přihlášení uživatele
        if($_SESSION["prihlasen"] == true){

            // Dotaz pro ziskani dat z databaze
            $transactions = Db::queryAll(" SELECT *
            FROM transakce;
            ");

            $this->data["transakce"] = $transactions;
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
