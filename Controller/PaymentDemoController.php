<?php
// Třída pro zpracování formuláře ukázkového dema
class PaymentDemoController extends Controller {
    public function execute($parameters){
        $this->data["error"] = "";
        // Hodnota 1 odpovídá konstantě GOPAY ve třídě Payment 
        $platebniBrana = 1;

        // Switch pro volbu platební brány
        switch ($platebniBrana) {
            case Payment::GOPAY:
                $payment = new GoPayPayment;
                break;
            
            default:
            $payment = new GoPayPayment;
                break;
        }
        // Zkontrolujte, zda je nastaven parametr "id" v GET 
        if(isset($_GET["id"])){
            $this->view = "pay";

            $id = $_GET["id"];

            // Získá stav platby
            $paymentState = $payment->getStatus($id);

            $this->data["stavObjednavky"] = $paymentState;
            
            // Pokud není objednávka zaplacena získá odkaz na platební branu 
            // a nastaví zprávu pro uživatele
            if($paymentState != "PAID"){
                $paymentIformations = $payment->getIformation($id);

                $this->data["gopay_url"] = $paymentIformations["gw_url"];
                $this->data["stavObjednavky"] = "Objednávka nebyla zaplacena";
            } 
            else {
                // Přesměrování na zaplaceno
                $this->redirect("payed");
            }
        }
        else{
            $this->view = "form";
            // Kontrola, zda byl odeslán formulář
            if(isset($_POST["jmeno"])){
                
                // Vytvoří instanci třídy Formular pro kontrolu dat
                $dataValidation = new FormValidation;
    
                // Převedení dat z $_POST na asociativní pole
                $formData = array();
                foreach ($_POST as $key => $value) {
                    $formData[$key] = $value;
                }
                // Kontrola validity dat
                if($dataValidation->vilidatePaymentDemo($formData)){

                    // Vloží data z formuláře do databáze
                    $query = Db::queryAndReturnId(
                        "INSERT INTO transakce
                        VALUES(NULL, '{$formData['oddeleni']}',
                        '{$formData['jmeno']}', '{$formData['prijmeni']}', '{$formData['email']}',
                        '{$formData['telefon']}', '{$formData['mesto']}', '{$formData['ulice']}', '{$formData['CP']}', '{$formData['PSC']}',
                        '{$formData['castka']}', NULL, NULL, NOW(), NULL,NULL
                        );"
                    );
                    // Pokud je výsledek boolean, došlo k chybě, přesměruje uživatele na error
                    if(is_bool($query)) {
                        $this->redirect("error");
                    } else {
                        // Přidá další údaje k datům formuláře
                        $formData["oddeleni"] = "platebni_brana";
                        $formData["cislo_objednavky"] = $query;
                        
                        // Přidá 2 nuly, protože pro GoPay api má částka i haléře
                        $formData["castka"] *= 100;
                        $urlParameters["buyerData"] = $formData;
                        $urlParameters["returnURL"] = "http://$_SERVER[HTTP_HOST]/PaymentDemo";

                        //TODO dát zde správný odkaz pro příjem notifikací, asi to bude model
                        $urlParameters["notificationURL"] = "http://$_SERVER[HTTP_HOST]/Model/Notifications";

                        // Získá url přes GoPay api
                        $url = $payment->getUrl($urlParameters);
                        
                         // Pokud URL obsahuje "error", došlo k chybě, jinak nastaví pohled na zaplatit
                        if(str_contains($url,"error")){
                            $this->redirect("error");
                        } else {
                            $this->data["gopay_url"] = $url;
                            $this->view = "pay";
                        }
                    }
                } 
                else {
                    // Pokud kontrola dat selže, nastaví chybovou zprávu
                    $this->data["error"] = $dataValidation->getErrorMSG();
                }
            }
        }
    }
}
