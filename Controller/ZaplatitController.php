<?php
class ZaplatitController extends Controller
{   

  
    public function execute($parameters)
    {
        
        
        $current_url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        
        $payment = new Payment;
        $url = $payment->getGoPayUrl("zde prijdou data",$current_url);
        $this->data["url"] = $url;
        $this->view = "zaplatit";
        //data posles pres $parameters
    
        if(isset($_GET["id"])){
            $response = $payment->getStatus($_GET["id"]);
      
            $responseArray = json_decode($response,true );

            // print_r($responseArray);
            

            $state = $responseArray["state"];
            
            $this->data["cisloObjednavky"] = $responseArray["order_number"];

            $this->data["state"] = $state;

            if($state != "PAID"){
      
              
              $url = $responseArray["gw_url"];
              $this->data["url"] = $url;
              
              
              $this->data["state"] = "Objednávka nebyla zaplacena";
            
            }
            else{
              $this->view = "zaplaceno";
            //   header("Location: zaplaceno.phtml" . "?cisloObjednavky=" . $cisloObjednavky);
            }     
        }     
    }
}
