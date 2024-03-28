<?php
class ApiController extends Controller {
    const API_TOKEN = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE3MTE0NjY5MjgsImV4cCI6MTc0NzQ2NjkyOCwicm9sZXMiOlsiUk9MRV9VU0VSIiwiUk9MRV9EQVRBX1JFQUQiLCJST0xFX0RBVEFfQ1JFQVRFIiwiUk9MRV9EQVRBX0VESVQiLCJST0xFX0RBVEFfREVMRVRFIiwiUk9MRV9DSEFOR0VfT1JERVJfU1RBVEUiXSwiY3VzdG9tZXJfZW1haWwiOiJhZG1pbkBzcHNlaW9zdHJhdmEuY3oifQ.VD0SOKmkhDLq5kBfkQYYsnscbeg2oMzZE9ipf45s99uaSEqFEINREf2ZpBPGhU25u0X82I9CqKpDOImmsIPvCfgwwhTzuN_2e9jYcK64UKaMgH9mYpBzxOwhgBxiVFa8sl0E4ITo2TqUKvGQtiIq4yQuFmz8pvxqs3XNNkGlWezmOwyFLVn_errJsXENfObjXnc5eVj1Kp-GnhPCvfSLX_uQYu8MOpGlB3Smkq1YqV6H4h6n72-ROB21E3TgxQ1XjPdbVJtQx8Rrm-QTN5qchJ3GL5Oz-J0OqAmaePK6r13sc7ZQ3wRyKC9_sRPjm2EbeZsn-53xvtyRMedjo4dH1w";
    
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

                $query = "SELECT url, notification_url FROM oddeleni WHERE nazev = '$oddeleni'";
                $url = Db::queryOne($query);
                if($url == 1){
                    $this->redirect("error");
                }
                else{
                    if($oddeleni == "platebni_brana"){
                        print "id=$cislo_objednavky&stav=$paymentState[state]&castka=$paymentState[amount]";
                    } else {
                        //view waiting
                        $this->view = "waiting";
                        //send data to the correct department
                        //send token in the header
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url['notification_url']);
                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, 
                            HTTP_BUILD_QUERY(array(
                                "id" => $cislo_objednavky, 
                                "stav" => $paymentState['state'], 
                                "castka" => $paymentState['amount']
                            ))
                        );
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                            'Authorization: Bearer '. self::API_TOKEN
                        ));
                        $response = curl_exec($ch);
                        curl_close($ch);
                        /*var_dump($response);
                        var_dump(HTTP_BUILD_QUERY(array(
                            "id" => $cislo_objednavky, 
                            "stav" => $paymentState['state'], 
                            "castka" => $paymentState['amount']
                        )));*/

                        //redirect to the correct department
                        $redirectURL = $url['url']."?".http_build_query(array(
                            "id" => $cislo_objednavky
                        ));
                        header("Location: $redirectURL");
                    }
                }
            }



        } else {    
            if(isset($_GET["email"]) && isset($_GET["castka"]) && isset($_GET["cislo_objednavky"]) && isset($_GET["oddeleni"])) {    
                $udaje = array();

                $udaje["email"] = $_GET["email"];
                $udaje["castka"] = $_GET["castka"];
                $udaje["cislo_objednavky"] = $_GET["cislo_objednavky"];
                $udaje["jmeno"] = $_GET["jmeno"]??null;
                $udaje["prijmeni"] = $_GET["prijmeni"]??null;
                $udaje["telefon"] = $_GET["telefon"]??null;
                $udaje["mesto"] = $_GET["mesto"]??null;
                $udaje["ulice"] = $_GET["ulice"]??null;
                $udaje["CP"] = $_GET["CP"]??null;
                $udaje["PSC"] = $_GET["PSC"]??null;
                $udaje["oddeleni"] = $_GET["oddeleni"];

                //castka poslední dve cisla jsou desetiná část
                $castka = $udaje["castka"]/100;

                $request=$_SERVER['QUERY_STRING'];
                //log to console
                //print $request;

                //hodit data do database a vratit ID pokud nejsou nastaveny dat null
                $query = Db::queryAndReturnId(
                    "INSERT INTO transakce
                    VALUES(NULL, '{$udaje['oddeleni']}',
                    '{$udaje['jmeno']}', '{$udaje['prijmeni']}', '{$udaje['email']}',
                    '{$udaje['telefon']}', '{$udaje['mesto']}', '{$udaje['ulice']}', '{$udaje['CP']}', '{$udaje['PSC']}',
                    '{$castka}', NULL, NULL, NOW(), NULL, '{$request}', {$udaje['cislo_objednavky']}
                    );"
                );

                /*$query = DB::queryAndReturnId(
                    'INSERT INTO transakce VALUES(NULL,?,?,?,?,?,?,?,?,?,?,?,?,NULL,NULL,NOW(),NULL,?,?)',
                    [
                        $udaje['oddeleni'],
                        $udaje['jmeno'],
                        $udaje['prijmeni'],
                        $udaje['email'],
                        $udaje['telefon'],
                        $udaje['mesto'],
                        $udaje['ulice'],
                        $udaje['CP'],
                        $udaje['PSC'],
                        $udaje['castka'],
                        $request,
                        $udaje['cislo_objednavky']
                    ]);*/

                //print $query;
                if(is_bool($query)) {
                    //$this->redirect("error");
                } else {
                    $udaje["cislo_objednavky"] = $query;
                    $urlParameters["buyerData"] = $udaje;
                    $urlParameters["returnURL"] = "http://$_SERVER[HTTP_HOST]/Api?brana=$platebniBrana";
                    $urlParameters["notificationURL"] = "http://$_SERVER[HTTP_HOST]/Notification";
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
                        $url = $url."?id='{$udaje['cislo_objednavky']}'&error=platbu_se_nepodařilo_vytvořit";
                        header("Location: $url");
                    }
                } else {
                    
                    header("Location: $url");
                }

                //print json_encode(["url" => $url]);
            }
            else{
                //print "error";
                if(isset($_GET["oddeleni"])){
                    $oddeleni = $_GET["oddeleni"];
                    $query = "SELECT url FROM oddeleni WHERE nazev = '$oddeleni'";
                    $url = Db::queryOne($query);
                    if($url == 1){
                        $this->redirect("error");
                    }
                    else{
                        $url = $url['url']."?error=nebyly_vyplněny_všechny_údaje";
                        header("Location: $url");
                    }
                }
                else{
                    $this->redirect("error");
                }
            }
        }
        
    }
}