<?php
class ApiController extends Controller {
    public function execute($parameters)
    {
        if(isset($_GET["brana"])){
            $platebniBrana = $_GET["brana"];
        }
        else{
            $platebniBrana = 1;
        }
        // Switch pro volbu platební brány
        switch ($platebniBrana) {
            case Payment::GOPAY:
                $payment = new GoPayPayment;
                break;
            
            default:
            $payment = new GoPayPayment;
                break;
        }

        if(isset($_GET["email"]) && isset($_GET["castka"])){    
            $udaje = array();
            foreach ($_GET as $key => $value) {
                $udaje[$key] = $value;
            }
            $udaje["oddeleni"] = "platebni_brana";
            $urlParameters["buyerData"] = $udaje;
            $urlParameters["returnURL"] = "http://$_SERVER[HTTP_HOST]/PaymentDemo";
            $urlParameters["notificationURL"] = "http://$_SERVER[HTTP_HOST]/Model/Notifications";
            $url = $payment->getUrl($urlParameters);

            //print json_encode(["url" => $url]);
            header("Location: $url");
        }
        else{
            print "error";
        }
        
    }
}