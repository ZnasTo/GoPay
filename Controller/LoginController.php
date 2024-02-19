<?php
// Třída pro správu přihlášení
class LoginController extends Controller{
    public function execute($parameters)
    {   
        // Zjistí jestli je uživatel přihlášen, pokud ano přesěruje ho na úvod
        if($_SESSION["prihlasen"] == true) {
            $this->redirect("mainpage");
        }

        $this->view = "login";

        //Zpravá pro uživatele, když něco zadá špatně
        $this->data["error"] = "";

        // generovani hesla
        // $hashnuteHeslo = password_hash("test", PASSWORD_BCRYPT); // hashnuti hesla
        // print($hashnuteHeslo);

        if(isset($_POST["jmeno"]) && isset($_POST["heslo"])){
            $jmeno = $_POST['jmeno'];
            $heslo = $_POST['heslo'];

            $login = new Login();

            // Ověří přihlašovací údaje, jinak vypíše error message
            if($login->login($jmeno,$heslo)){
                $this->redirect("mainpage");
            } else {
                $this->data["error"] = $login->getErrorMsg(); 
            }
    
        }
        


    }

}