<?php
// Třída pro vypsání requestu, který přišel na naše api
class ShowrequestController extends Controller
{
    public function execute($parameters) {


        if($_SESSION["prihlasen"] == true){
            // Kontrola jestli je zadané id transakce
            if(isset($_GET["id_transakce"])) {
    
                $id = $_GET["id_transakce"];
    
                $this->view = "showRequest";
                $result = Db::queryOne("SELECT request 
                    FROM transakce 
                    WHERE $id = id_transakce"
                );
                
                $this->data["request"] = $result["request"];
    
            } else {
                // Přesměrování zpátky na správu
                $this->redirect("administration");
            }
        }
        else{
            // Přesměrování na login
            $this->redirect("login");
        }

    }
}
