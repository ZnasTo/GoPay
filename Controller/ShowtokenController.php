<?php
// Třída pro vypsání requestu, který přišel na naše api
class ShowtokenController extends Controller
{
    public function execute($parameters) {


        if($_SESSION["prihlasen"] == true){
            // Kontrola jestli je zadané id transakce
            if(isset($_GET["department"])) {
    
                $oddeleni = $_GET["department"];
    
                $this->view = "showToken";
                $result = Db::queryOne("SELECT api_token
                    FROM oddeleni 
                    WHERE nazev = ?", 
                    array($oddeleni)
                );
                
                $this->data["api_token"] = $result["api_token"];
    
            } else {
                // Přesměrování zpátky na správu
                $this->redirect("department");
            }
        }
        else{
            // Přesměrování na login
            $this->redirect("login");
        }

    }
}