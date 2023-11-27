<?php 
class SpravaController extends Controller{
    public function execute($parameters){

        // $this->redirect("upravit");
        if($_SESSION["prihlasen"]== true){

            
            $transakce = Db::dotazVsechny(" SELECT *
            FROM transakce;
            ");
        
            if (isset($_GET["odstranit"])) {
                $idTransakce = $_GET["odstranit"];
                Db::odstran("transakce","id_transakce",$idTransakce);; 
                $this->redirect("sprava");
            }
            $this->data["transakce"] = $transakce;
            $this->view = "sprava";
            
            if (isset($_GET["upravit"])) {
                $idTransakce = $_GET["upravit"];
                // $_POST["neco"] = "neco";
                $this->redirect("upravit?id_transakce=$idTransakce");
            }
            
        }
        else{
            $this->redirect("login");
            
            
        }
        
    }


}
