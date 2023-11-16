<?php
// TODO pokud je prihlaseny, nechtej po nem heslo\
//TODO odhlaseni
class LoginController extends Controller{
    
    
    public function execute($parameters)
    {   
        
        // if($_SESSION["sprava"]==1){
        //     $this->redirect("sprava");
        // }

        $this->view = "login";

        //zprava pro uzivatele, kdyz zada neco spatne
        $this->data["error"] = "";
        

        // $hashnuteHeslo = password_hash("test", PASSWORD_BCRYPT); // hashnuti hesla
        // print($hashnuteHeslo);
        if(isset($_POST["jmeno"]) && isset($_POST["heslo"])){
            $jmeno = $_POST['jmeno'];
            $heslo = $_POST['heslo'];

            //vrati pocet kolikrat se vyskytuje jmeno v datavazi
                // jelikoz PK tak maximalne 1 nebo 0 pokud se nevyskytuje v databazi
            $dotazJmeno = Db::dotaz("SELECT jmeno
            FROM uzivatele
            WHERE jmeno = '$jmeno'");

            if($dotazJmeno == 1){
                $dotazHeslo = Db::dotazJeden("SELECT heslo
                FROM uzivatele
                WHERE jmeno = '$jmeno'");
                $hash = $dotazHeslo["heslo"];

                // print_r($result);

                //kontrola hesla
                if (password_verify($heslo, $hash)) {
                    $_SESSION["sprava"] = 1;
                    $redirect = new RedirectController;
                    $redirect->redirect("sprava");
                } else {
                    $this->data["error"] = "Špatné Heslo";
                
                }
            } else{
                $this->data["error"] = "Špatné Jméno";
            }
 
        }

    }

}