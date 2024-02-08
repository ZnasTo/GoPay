<?php
class ShowRequestController extends Controller
{
    public function execute($parameters) {
        if(isset($_GET["id_transakce"])) {
            $id = $_GET["id_transakce"];
            if($_SESSION["prihlasen"]==true) {
                $this->view = "showRequest";
                $result = Db::dotazJeden("SELECT request 
                FROM transakce 
                WHERE $id = id_transakce");

                // print_r($result);
                // print($result["request"]);
                $this->data["request"] = $result["request"];

            }
        } else {
            $this->redirect("sprava");
        }

    }
}
