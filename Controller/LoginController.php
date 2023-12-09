<?php

class LoginController extends Controller{
    
    
    public function execute($parameters)
    {   
        //Zjisti jestli je uzivatel prihlasen, pokud ano presmeruje ho na uvod
        if($_SESSION["prihlasen"] == true)
            $this->redirect("uvod");

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
    
                $login = new Login();


                if($login->prihlas($jmeno,$heslo)){
                    $this->redirect("uvod");
                } else {
                    $this->data["error"] = $login->getErrorMsg(); 
                }
     
            }
        


    }

}