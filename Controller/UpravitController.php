<?php

class UpravitController extends Controller
{   
    public function execute($parameters){
       
        if($_SESSION["prihlasen"]==true){
            $this->view = "upravit";
            
            if(isset($_POST["jmeno"])){

                
                $dataZFormulare = array();
                foreach ($_POST as $key => $value) {
                    $dataZFormulare[$key] = $value;
                }

                $validniZpusobPlatby = true;
                //nastaveni cas_zaplaceni a zpusob platby, ktere  jsou zavisle na tom, jestli byla objednavka zaplacena
                if(!$dataZFormulare["byla_zaplacena"] && $dataZFormulare["zaplaceno"]){
                    $dataZFormulare["cas_zaplaceni"] = "cas_zaplaceni = NOW() ";  
                    if(!isset($dataZFormulare["zpusob_platby"])){
                        $validniZpusobPlatby = false;
                        //TODO zakomponovat bud do validace nebo nejak proste vyhodit error
                    } else {
                        $dataZFormulare["zpusob_platby"] = "zpusob_platby = '$dataZFormulare[zpusob_platby]'";
                    }
                } else if(!$dataZFormulare["zaplaceno"]){
                    $dataZFormulare["cas_zaplaceni"] = "cas_zaplaceni = NULL ";
                    $dataZFormulare["zpusob_platby"] = "zpusob_platby = NULL ";
                } else {
                    $dataZFormulare["zpusob_platby"] = "zpusob_platby = '$dataZFormulare[zpusob_platby]'";
                    $dataZFormulare["cas_zaplaceni"] = "cas_zaplaceni = STR_TO_DATE('$dataZFormulare[cas_zaplaceni]', '%d.%m.%Y %H:%i:%s')";
                }
                
                
                $form = new Formular;
                if ($form->kontrolaDat($dataZFormulare) && $validniZpusobPlatby) {
                    //upraveni dat v databazi
                    Db::dotaz("UPDATE transakce
                    SET oddeleni = '$dataZFormulare[oddeleni]',jmeno = '$dataZFormulare[jmeno]',prijmeni ='$dataZFormulare[prijmeni]',email ='$dataZFormulare[email]',
                    telefon ='$dataZFormulare[telefon]',mesto = '$dataZFormulare[mesto]',ulice ='$dataZFormulare[ulice]',CP='$dataZFormulare[CP]',PSC = '$dataZFormulare[PSC]',castka ='$dataZFormulare[castka]'
                    ,$dataZFormulare[zpusob_platby], zaplaceno = '$dataZFormulare[zaplaceno]',$dataZFormulare[cas_zaplaceni]
                    WHERE id_transakce = '$dataZFormulare[id_transakce]'");
                } else {
                    //TODO error handeling
                    $error = $form->getErrorMSG();
                    $this->redirect("sprava?=$error");
                }



            }

            
            //overujeme ze je zadane id
            if (isset($_GET["id_transakce"])) {
                // $this->id = $_GET["id_transakce"];
                
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

                $this->data["transakce"]["byla_zaplacena"] = $this->data["transakce"]["zaplaceno"];
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
                // $test =  Formular::kontrolaDat($_POST);
                $this->redirect("sprava?=neco");
            }


        }
        else {
            $this->redirect("login");
        }
    }
}
