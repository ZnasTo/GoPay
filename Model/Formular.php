<?php
//TODO pridat atribut error 
//TODO pridat funkci getErrorMsg
class Formular
{
    //vraci true, kdyz jsou data validni a flase, kdyz nejsou 
    public static function kontrolaDat($parameters = array()){

        if (!isset($parameters["id_transakce"])) {
            return false;
        }
        if (isset($parameters["oddeleni"])) {

            $dotaz = Db::dotaz("SELECT nazev
            FROM oddeleni
            WHERE nazev = '$parameters[oddeleni]'
            ");
            
            if ($dotaz != 1) {
                return false;
            }
        } else {
            return false;
        }
        if (isset($parameters["jmeno"])) {
            if (intval($parameters["jmeno"]) != 0) {
                return false;
            } 
            if (strlen($parameters["jmeno"]) > 30) {
                return false;
            }

        } else {
            return false;
        }

        if (isset($parameters["prijmeni"])) {
            if (intval($parameters["prijmeni"]) != 0) {
                return false;
            } 
            if (strlen($parameters["prijmeni"]) > 30) {
                return false;
            }
        } else {
            return false;
        }

        if (isset($parameters["email"])) {
            if (!filter_var($parameters["email"], FILTER_VALIDATE_EMAIL)) {
                return false;

            }
        } else {
            return false;
        }

        if (isset($parameters["telefon"])) {
            //muzou v nem byt pouze cislice, musi jich byt 12
            if (!preg_match('/^[0-9]{12}+$/', $parameters["telefon"])) {
                return false;
            }
        } else {
            return false;
        }

        if (isset($parameters["ulice"])) {
            if (intval($parameters["ulice"]) != 0) {
                return false;
            } 
            if (strlen($parameters["ulice"]) > 60) {
                return false;
            }
        } else {
            return false;
        }

        if (isset($parameters["mesto"])) {
            if (intval($parameters["mesto"]) != 0) {
                return false;
            } 
            if (strlen($parameters["mesto"]) > 58) {
                return false;
            }
        } else {
            return false;
        }

        if (isset($parameters["PSC"])) {
            //muzou v nem byt pouze cislice, musi jich byt 5
            if (!preg_match('/^[0-9]{5}+$/', $parameters["PSC"])) {
                return false;

            }
        } else {
            return false;
        }

        if (isset($parameters["CP"])) {
            //muzou v nem byt pouze cislice nebo '/', musi jich byt 1-10
            if (!preg_match('/^[0-9\/]{1,10}+$/', $parameters["CP"])) {
                return false;

            }
        } else {
            return false;
        }

        if (isset($parameters["castka"])) {
            if ($parameters["castka"] != 0) {
                if (intval($parameters["castka"]) == 0) {
                    return false;
                } 
            } 
            if ($parameters["castka"] < 0) {
                return false;
            } 

        } else {
            return false;
        }
        if (isset($parameters["zaplaceno"])) {

            //TODO proc to nefunguje
            if($parameters["zaplaceno"] != 0 || $parameters["zaplaceno"] != 1) {
                return true;
            }
            
            // if($parameters["zaplaceno"]== 0){
            //     // if (isset($parameters["zpusob_platby"])) {
            //     //     return false;
            //     // }
            //     // else {
            //     //     return false;
            //     // }
            //     if (isset($parameters["cas_zaplaceni"])) {
            //         return false;
            //     }
            //     // $zaplaceno = 0;
            // } else {
            //     if (isset($parameters["zpusob_platby"])) {
            //         //TODO povolene zpusoby platby, musim zjistit
            //     } else {
            //         return false;
            //     }
            // }

            
        }


        if (!isset($parameters["cas_vytvoreni"]) ) {
            return false;
        }

        

        //zpusob platby

        


        return true;
    }
    
}
