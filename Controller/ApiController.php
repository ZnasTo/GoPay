<?php
class ApiController extends Controller {
    public function execute($parameters)
    {
        $platba = new Payment;

        if(isset($_GET["email"]) && isset($_GET["castka"])){    
            $udaje = array();
            foreach ($_GET as $key => $value) {
                $udaje[$key] = $value;
            }
            $udaje["oddeleni"] = "platebni_brana";
            $url = $platba->getGoPayUrl($udaje,"http://$_SERVER[HTTP_HOST]/formular");

            //print json_encode(["url" => $url]);
            header("Location: $url");
        }
        else{
            print "error";
        }
        
    }
}