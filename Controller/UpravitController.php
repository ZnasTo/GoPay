<?php

class UpravitController extends Controller
{
    public function execute($parameters){
       
        if($_SESSION["sprava"]==1){
            $this->view = "upravit";
            
            if(isset($_POST["jmeno"])){
                //TODO dodealt insert... platebni metody, zda bylo zaplaceno, cas vytvoreni (jesli je zaplaceno, dej now)
                Db::dotaz("UPDATE transakce WHERE id_transakce = '$_POST[id_transakce]'
                VALUES ('$_POST[id_transakce]','$_POST[oddeleni]','$_POST[jmeno]','$_POST[prijmeni]','$_POST[email]',
                '$_POST[telefon]','$_POST[mesto]','$_POST[ulice]','$_POST[CP]','$_POST[PSC]','$_POST[castka]','CARD',true,
                '$_POST[cas_vytvoreni]','2011-12-18 13:17:17')"
            
            );
            }

            
            //overujeme ze je zadane id
            if(!isset($_GET["id_transakce"]))
                $this->redirect("sprava");

            //ziskani informaci o transakci
            $this->data["transakce"] = Db::dotazJeden("SELECT *
            FROM transakce
            WHERE id_transakce = $_GET[id_transakce]");

            $this->data["transakce"]["cas_vytvoreni"] = date("j.n.Y H:i:s", strtotime($this->data["transakce"]["cas_vytvoreni"]));
           
           //kontrola jestli element existuje (neni v databazi zapsano NULL)
            if(isset($this->data["transakce"]["caz_zaplaceni"])){
                
                $this->data["transakce"]["caz_zaplaceni"] = "";
            }else{
                $this->data["transakce"]["cas_zaplaceni"] = date("j.n.Y H:i:s", strtotime($this->data["transakce"]["cas_zaplaceni"]));
            }

            //nastaveni hodnoty do selectu
            if($this->data["transakce"]["zaplaceno"]){
                $this->data["transakce"]["zaplaceno"] = "
                <option value='1'>Ano</option>
                <option value='0'>Ne</option>
                ";
            } else {
                $this->data["transakce"]["zaplaceno"] = "
                <option value='0'>Ne</option>
                <option value='1'>Ano</option>
                ";
            }

            // if(isset($_POST["upravit"])){
            //     print("ahoj");
            // }
        }
        else {
            $this->redirect("login");
        }
    }
}
