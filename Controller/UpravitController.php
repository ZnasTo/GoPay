<?php

class UpravitController extends Controller
{
    public function execute($parameters){
       
        if($_SESSION["prihlasen"]==true){
            $this->view = "upravit";
            
            if(isset($_POST["jmeno"])){

                

                $zaplaceno = 1;
                $cas_zaplaceni = "";

                if($_POST["zaplaceno"]== 0){
                    $zaplaceno = 0;
                    $cas_zaplaceni = ",cas_zaplaceni = NULL ";
                }
                if($_POST["cas_zaplaceni"] == "" && $zaplaceno){
                    $cas_zaplaceni = ",cas_zaplaceni = NOW() ";  
                }

                //TODO mozna validaci, aby nesel sql injection attack
                //upraveni dat v databazi
                // Db::dotaz("UPDATE transakce
                // SET oddeleni = '$_POST[oddeleni]',jmeno = '$_POST[jmeno]',prijmeni ='$_POST[prijmeni]',email ='$_POST[email]',
                // telefon ='$_POST[telefon]',mesto = '$_POST[mesto]',ulice ='$_POST[ulice]',CP='$_POST[CP]',PSC = '$_POST[PSC]',castka ='$_POST[castka]',zpusob_platby = '$_POST[zpusob_platby]',zaplaceno = $zaplaceno 
                // " . $cas_zaplaceni . "
                // WHERE id_transakce = '$_POST[id_transakce]'");

                print Formular::kontrolaDat($_POST);
            }

            
            //overujeme ze je zadane id
            if (isset($_GET["id_transakce"])) {

                
                //ziskani informaci o transakci
                $this->data["transakce"] = Db::dotazJeden("SELECT *
                FROM transakce
                WHERE id_transakce = $_GET[id_transakce]");
    
                $this->data["transakce"]["cas_vytvoreni"] = date("j.n.Y H:i:s", strtotime($this->data["transakce"]["cas_vytvoreni"]));
                
                //kontrola jestli element existuje (neni v databazi zapsano NULL)
                if(isset($this->data["transakce"]["cas_zaplaceni"]) == null){
    
                    $this->data["transakce"]["cas_zaplaceni"] = "";
                }else{
                    $this->data["transakce"]["cas_zaplaceni"] = date("j.n.Y H:i:s", strtotime($this->data["transakce"]["cas_zaplaceni"]));
                }
    
                //nastaveni selected hodnoty do selectu
                if($this->data["transakce"]["zaplaceno"]){
                    $this->data["transakce"]["zaplaceno"] = "
                    <option value='0'>Ne</option>
                    <option value='1' selected>Ano</option>
                    ";
                } else {
                    $this->data["transakce"]["zaplaceno"] = "
                    <option value='0' selected>Ne</option>
                    <option value='1'>Ano</option>
                    ";
                }
            } else {
                $test =  Formular::kontrolaDat($_POST);
                // if($test){
                //     $test = 1;
                // }
                // else
                //     $test = 2;
                $this->redirect("sprava?=$test");
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
