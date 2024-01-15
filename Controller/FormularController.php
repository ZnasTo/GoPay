<?php
class FormularController extends Controller {
    public function execute($parameters){
        
        $platba = new GoPayPayment;

        if(isset($_GET["id"])){
            $this->view = "zaplatit";
            $id = $_GET["id"];
            $this->data["idObjednavky"] = $id;
            
            $stavObjednavky = $platba->getStatus($id);

            // print $stavObjednavky;
            $this->data["stavObjednavky"] = $stavObjednavky;

            
            if($stavObjednavky != "PAID"){
                $informaceObjednavka = $platba->getIformation($id);
                // print_r($informaceObjednavka);
                $this->data["gopay_url"] = $informaceObjednavka["gw_url"];

            
                $this->data["stavObjednavky"] = "Objednávka nebyla zaplacena";
            
            } else {
                //TODO updatnout hodnoty v databazi
                print_r($platba->getIformation($id));
                $informaceOPlatbe = $platba->getIformation($id);
                // json_decode($platba->getIformation($id));
                Db::dotaz("UPDATE transakce SET zaplaceno = 1,cas_zaplaceni = NOW(),zpusob_platby = '{$informaceOPlatbe['payment_instrument']}' WHERE id_transakce = '{$informaceOPlatbe['order_number']}'");
                $this->redirect("zaplaceno");
            }
        }
        else{
            $this->view = "formular";
            if(isset($_POST["jmeno"])){
                $kontrolaDat = new Formular;
    
                $dataZFormulare = array();
                foreach ($_POST as $key => $value) {
                    $dataZFormulare[$key] = $value;
                }


                // "INSERT into transakce
                // VALUES(NULL, '{$dataZFormulare['oddeleni']}',
                //     '{$dataZFormulare['jmeno']}', '{$dataZFormulare['prijmeni']}', '{$dataZFormulare['email']}',
                //     '{$dataZFormulare['telefon']}', '{$dataZFormulare['mesto']}', '{$dataZFormulare['ulice']}', '{$dataZFormulare['CP']}', '{$dataZFormulare['PSC']}',
                //     '{$dataZFormulare['castka']}', NULL, 0, NOW(), NULL,NULL
                // );"
                //vlozi dotaz do databaze a vrati id 
                $dotaz = Db::vlozAVratId(
                    "INSERT into transakce
                    VALUES(NULL, '{$dataZFormulare['oddeleni']}',
                    '{$dataZFormulare['jmeno']}', '{$dataZFormulare['prijmeni']}', '{$dataZFormulare['email']}',
                    '{$dataZFormulare['telefon']}', '{$dataZFormulare['mesto']}', '{$dataZFormulare['ulice']}', '{$dataZFormulare['CP']}', '{$dataZFormulare['PSC']}',
                    '{$dataZFormulare['castka']}', NULL, 0, NOW(), NULL,NULL
                );"
                );

                if(is_bool($dotaz)) {
                    // TODO error
                } else {
                    // print($dotaz);
        
                    $dataZFormulare["oddeleni"] = "platebni_brana";
                    $dataZFormulare["cislo_objednavky"] = $dotaz;
                    // print_r($dataZFormulare);
                    // if($kontrolaDat->kontrolaDatZaplatit($dataZFormulare)){
                    // $urlParametry = array();
                    $urlParametry["buyerData"] = $dataZFormulare;
                    $urlParametry["returnURL"] = "http://$_SERVER[HTTP_HOST]/formular";

                    // $url = $platba->getUrl($dataZFormulare,"http://$_SERVER[HTTP_HOST]/formular");
                    $url = $platba->getUrl($urlParametry);

                    // dotaz
                    // print_r($platba->getIformation());
                    if(str_contains($url,"error")){
                        //TODO error handeling 
                        print $url;
                    } else {
                        $this->data["gopay_url"] = $url;
                        // print($url);
                        $this->view = "zaplatit";
                    }
                }
                // } else {
                //     $error = $kontrolaDat->getErrorMSG();
                //     print($error);
                // }
            }
        }
        }
}