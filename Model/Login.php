<?php

class Login {
    
    private $errorMsg = "";

    // Pokud jsou přihlašovací údaje správné provede se přihlášení a metoda vrátí true
    // ,jinak vrátí false
    public function login($jmeno,$heslo) {

         // Vrátí kolikrát se jmeno vyskytuje v databázi
            // Jelikož jméno je PK tak maximalne 1 nebo 0 pokud se jméno v databázi nevyskytuje
        $dotazJmeno = Db::query("SELECT jmeno
            FROM uzivatele
            WHERE jmeno = '$jmeno'");
    
        if($dotazJmeno == 1){
            $dotazHeslo = Db::queryOne("SELECT heslo
            FROM uzivatele
            WHERE jmeno = '$jmeno'");
            $hash = $dotazHeslo["heslo"];

            // Kontrola hesla
            if (password_verify($heslo, $hash)) {
                $_SESSION["prihlasen"] = true;
                return true;
            } else {
                $this->errorMsg = "Špatné Heslo";
                return false;
            }
        } else{
            $this->errorMsg = "Špatné Jméno";
            return false;
        }

    }
    // vrátí chybovou hlášku
    public function getErrorMsg(){
        return $this->errorMsg;
    }


}