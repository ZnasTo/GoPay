<?php 
class SpravaController extends Controller{
    public function execute($parameters){
        $redirect = new RedirectController;

        if($_SESSION["sprava"]==1){
            
            $dotaz = Db::dotazVsechny("
            // SELECT * 
            // FROM objednavka INNER JOIN adresa USING(id_adresy) 
            // INNER JOIN zpusobplatby USING(id_platby) 
            // INNER JOIN zakaznici USING(id_zakaznika);
            ");
        
            if (isset($_GET["odstranit"])) {
                $idObjednavky = $_GET["odstranit"];
                Db::odstran("objednavka","cislo",$idObjednavky);; 
                $redirect->redirect("sprava");
            }
            $this->data["dotaz"] = $dotaz;
            $this->view = "sprava";
        }
        else{
            $redirect->redirect("login");

        }
    }


}
