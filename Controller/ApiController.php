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
            $paymentState = $payment->getInformation($id);
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

                $udaje["email"] = $_GET["email"];
                $udaje["castka"] = $_GET["castka"];
                $udaje["cislo_objednavky"] = $_GET["cislo_objednavky"];
                $udaje["jmeno"] = $_GET["jmeno"];
                $udaje["prijmeni"] = $_GET["prijmeni"];
                $udaje["telefon"] = $_GET["telefon"];
                $udaje["mesto"] = $_GET["mesto"];
                $udaje["ulice"] = $_GET["ulice"];
                $udaje["CP"] = $_GET["CP"];
                $udaje["PSC"] = $_GET["PSC"];
                $udaje["oddeleni"] = $_GET["oddeleni"];
                

                //hodit data do database a vratit ID pokud nejsou nastaveny dat null
                $query = Db::queryAndReturnId(
                    "INSERT INTO transakce
                    VALUES(NULL, '{$udaje['oddeleni']}',
                    '{$udaje['jmeno']}', '{$udaje['prijmeni']}', '{$udaje['email']}',
                    '{$udaje['telefon']}', '{$udaje['mesto']}', '{$udaje['ulice']}', '{$udaje['CP']}', '{$udaje['PSC']}',
                    '{$udaje['castka']}', NULL, NULL, NOW(), NULL,NULL, {$udaje['cislo_objednavky']}
                    );"
                );
                print $query;
                if(is_bool($query)) {
                    //$this->redirect("error");
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
                        //$this->redirect("error");
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