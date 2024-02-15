<?php
// Třída pro vypsání requestu, který přišel na naše api
class ShowRequestController extends Controller
{
    public function execute($parameters) {
        // Kontrola jestli je zadané id transakce
        if(isset($_GET["id_transakce"])) {

            $id = $_GET["id_transakce"];

            // Kontrola přihlášení uživatele
            if($_SESSION["prihlasen"]==true) {
                $this->view = "showRequest";
                $result = Db::queryOne("SELECT request 
                    FROM transakce 
                    WHERE $id = id_transakce"
                );
                
                $this->data["request"] = $result["request"];

            }
        } else {
            // Přesměrování zpátky na správu
            $this->redirect("administration");
        }

    }
}
