<?php
class FormularController extends Controller {
    public function execute($parameters){
        
        $platba = new Payment;

        if(isset($_GET["id"])){
            $this->view = "zaplatit";
            $id = $_GET["id"];
            $this->data["idObjednavky"] = $id;
            
            $stavObjednavky = $platba->getStatus($id);

            // print $stavObjednavky;
            $this->data["stavObjednavky"] = $stavObjednavky;

            
            if($stavObjednavky != "PAID"){
                $informaceObjednavka = $platba->getIformation($id);
    
                $this->data["gopay_url"] = $informaceObjednavka;

            
                $this->data["stavObjednavky"] = "Objednávka nebyla zaplacena";
            
            } else {
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
    
                $dataZFormulare["oddeleni"] = "platebni_brana";
                print_r($dataZFormulare);
                // if($kontrolaDat->kontrolaDatZaplatit($dataZFormulare)){
                    $url = $platba->getGoPayUrl($dataZFormulare,"http://$_SERVER[HTTP_HOST]/formular");
                    if(str_contains($url,"error")){
                        //TODO error handeling
                        print $url;
                    } else {
                        $this->data["gopay_url"] = $url;
                        $this->view = "zaplatit";
                    }
                // } else {
                //     $error = $kontrolaDat->getErrorMSG();
                //     print($error);
                // }
            }
        }
        }
}