<?php

class Login {
    
    private $errorMsg = "";

    //pokud se povede prihlaseni vrati true jinak vrati false
    public function prihlas($jmeno,$heslo) {

         //vrati pocet kolikrat se vyskytuje jmeno v databazi
            // jelikoz PK tak maximalne 1 nebo 0 pokud se nevyskytuje v databazi
        $dotazJmeno = Db::dotaz("SELECT jmeno
            FROM uzivatele
            WHERE jmeno = '$jmeno'");
    
        if($dotazJmeno == 1){
            $dotazHeslo = Db::dotazJeden("SELECT heslo
            FROM uzivatele
            WHERE jmeno = '$jmeno'");
            $hash = $dotazHeslo["heslo"];

            //kontrola hesla
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
    public function getErrorMsg(){
        return $this->errorMsg;
    }


}