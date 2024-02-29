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

        if (isset($_GET["id"])) {
            $id = $_GET["id"];
            $paymentState = $payment->getIformation($id);
            //get transaction information (oddeleni) from our database
            $query = "SELECT oddeleni, cislo_objednavky FROM transakce WHERE id_transakce = $paymentState[order_number]";
            $vysledek = Db::queryOne($query);
            if($vysledek == 1){
                $this->redirect("error");
            }
            else{
                $oddeleni = $vysledek["oddeleni"];
                $cislo_objednavky = $vysledek["cislo_objednavky"];

                $query = "SELECT url FROM oddeleni WHERE nazev = '$oddeleni'";
                $url = Db::queryOne($query);
                if($url == 1){
                    $this->redirect("error");
                }
                else{
                    if($oddeleni == "platebni_brana"){
                        print "id=$cislo_objednavky&stav=$paymentState[state]";
                    } else {
                        $url = $url['url']."?id=$cislo_objednavky&stav=$paymentState[state]";
                        header("Location: $url");
                    }
                }
            }



        } else {    
            if(isset($_GET["email"]) && isset($_GET["castka"]) && isset($_GET["cislo_objednavky"])) {    
                $udaje = array();
                foreach ($_GET as $key => $value) {
                    $udaje[$key] = $value;
                }
                //hodit data do database a vratit ID pokud nejsou nastaveny dat null
                $query = Db::queryAndReturnId(
                    "INSERT INTO transakce
                    VALUES(NULL, '{$udaje['oddeleni']}',
                    '".$udaje['jmeno']?$udaje['jmeno']:null."', '".$udaje['prijmeni']??null."', '{$udaje['email']}',
                    '".$udaje['telefon']??null."', '".$udaje['mesto']??null."', '".$udaje['ulice']??null."', '".$udaje['CP']??null."', '".$udaje['PSC']??null."',
                    '{$udaje['castka']}', NULL, NULL, NOW(), NULL,NULL, {$udaje['cislo_objednavky']}
                    );"
                );
                print $query;
                if(is_bool($query)) {
                    $this->redirect("error");
                } else {
                    $udaje["cislo_objednavky"] = $query;
                    $urlParameters["buyerData"] = $udaje;
                    $urlParameters["returnURL"] = "http://$_SERVER[HTTP_HOST]/Api?brana=$platebniBrana";
                    $urlParameters["notificationURL"] = "http://$_SERVER[HTTP_HOST]/Model/Notifications";
                    $url = $payment->getUrl($urlParameters);
                }

                if(str_contains($url,"error")){
                    //Získat url z spravneho oddělení z database
                    $query = "SELECT url FROM oddeleni WHERE nazev = '$udaje[oddeleni]'";
                    $url = Db::queryOne($query);
                    if(is_bool($query)){
                        $this->redirect("error");
                    }
                    else{
                        $url = $url."?error=platbu_se_nepodařilo_vytvořit";
                        header("Location: $url");
                    }
                } else {
                    
                    header("Location: $url");
                }

                //print json_encode(["url" => $url]);
            }
            else{
                print "error";
            }
        }
        
    }
}