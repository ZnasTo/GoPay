<?php 
class SpravaController extends Controller{
    public function execute($parameters){

        if($_SESSION["prihlasen"]== true){

            //query pro ziskani dat z databaze
            $transakce = Db::dotazVsechny(" SELECT *
            FROM transakce;
            ");

            //odstraneni zaznamu
            if (isset($_GET["odstranit"])) {
                $idTransakce = $_GET["odstranit"];
                Db::odstran("transakce","id_transakce",$idTransakce);; 
                $this->redirect("sprava");
            }
            $this->data["transakce"] = $transakce;
            $this->view = "sprava";

            //presmerovani na upravit   
            if (isset($_GET["upravit"])) {
                $idTransakce = $_GET["upravit"];
                $this->redirect("upravit?id_transakce=$idTransakce");
            }
            
        }
        else{
            $this->redirect("login");
            
            
        }
        
    }


}
