<?php
//TODO pridat atribut error 
//TODO pridat funkci getErrorMsg
class Formular
{
    // pokud overeni neprojde chybova hlaska se tady ulozi  
    private $errorMSG;
    public function getErrorMSG(){
        return $this->errorMSG;
    }
    //vraci true, kdyz jsou data validni a flase, kdyz nejsou 
    public function kontrolaDat($parameters = array()){

        if (!isset($parameters["id_transakce"])) {
            $this->errorMSG = "není zadané id";
            return false;
        }
        if (isset($parameters["oddeleni"])) {

            $dotaz = Db::dotaz("SELECT nazev
            FROM oddeleni
            WHERE nazev = '$parameters[oddeleni]'
            ");
            
            if ($dotaz != 1) {
                $this->errorMSG = "oddělení neexistuje";
                return false;
            }
        } else {
            $this->errorMSG = "není zadáno oddělení";
            return false;
        }
        if (isset($parameters["jmeno"])) {
            //muzou v nem byt pouze znaky podporovane v unicode
            if (!preg_match('/^[a-zA-Z\s]+$/u', $parameters["jmeno"])) {
                $this->errorMSG = "neplatné jméno";
                return false;
            }
            if (strlen($parameters["jmeno"]) > 30) {
                $this->errorMSG = "příliš dlouhé jméno";
                return false;
            }

        } else {
            $this->errorMSG = "jméno není zadáno";
            return false;
        }

        if (isset($parameters["prijmeni"])) {
            //muzou v nem byt pouze znaky podporovane v unicode
            if (!preg_match('/^[a-zA-Z\s]+$/u', $parameters["prijmeni"])) {
                $this->errorMSG = "neplatné příjmení";
                return false;
            }
            if (strlen($parameters["prijmeni"]) > 30) {
                $this->errorMSG = "příliš dlouhé příjmení";
                return false;
            }
        } else {
            $this->errorMSG = "příjmění není zadáno";
            return false;
        }

        if (isset($parameters["email"])) {
            if (!filter_var($parameters["email"], FILTER_VALIDATE_EMAIL)) {
                $this->errorMSG = "email není validní";
                return false;
            }
        } else {
            $this->errorMSG = "není zadán email";
            return false;
        }

        if (isset($parameters["telefon"])) {
            //muzou v nem byt pouze cislice, musi jich byt 12
            if (!preg_match('/^[0-9]{12}+$/', $parameters["telefon"])) {
                $this->errorMSG = "telefon není validní";
                return false;
            }
        } else {
            $this->errorMSG = "není zadán telefon";
            return false;
        }

        if (isset($parameters["ulice"])) {
            //muzou v nem byt pouze znaky podporovane v unicode a mezery,tecky,pomlcky
            //pozn. autora regex je wild
            if (!preg_match('/^[a-zA-Z\s.\-]+$/u', $parameters["ulice"])) {
                $this->errorMSG = "ulice není validní";
                return false;
            }
            if (strlen($parameters["ulice"]) > 60) {
                $this->errorMSG = "ulice je příliš dlouhá";
                return false;
            }
        } else {
            $this->errorMSG = "ulice není zadána";
            return false;
        }

        if (isset($parameters["mesto"])) {
            //muzou v nem byt pouze znaky podporovane v unicode a mezery,tecky,pomlcky
            if (!preg_match('/^[a-zA-Z\s.\-]+$/u', $parameters["mesto"])) {
                $this->errorMSG = "město není validní";
                return false;
            }
            if (strlen($parameters["mesto"]) > 58) {
                $this->errorMSG = "město je příliš dlouhé";
                return false;
            }
        } else {
            $this->errorMSG = "město není zadáno";
            return false;
        }

        if (isset($parameters["PSC"])) {
            //muzou v nem byt pouze cislice, musi jich byt 5
            if (!preg_match('/^[0-9]{5}+$/', $parameters["PSC"])) {
                $this->errorMSG = "PSČ není validní";
                return false;
            }
        } else {
            $this->errorMSG = "PSČ není zadáno";
            return false;
        }

        if (isset($parameters["CP"])) {
            //muzou v nem byt pouze cislice nebo '/', musi jich byt 1-10
            if (!preg_match('/^[0-9\/]{1,10}+$/', $parameters["CP"])) {
                $this->errorMSG = "číslo popisne není validní";
                return false;
            }
        } else {
            $this->errorMSG = "číslo popisné není zadáno";
            return false;
        }

        if (isset($parameters["castka"])) {
            if (intval($parameters["castka"]) <= 0) {
                $this->errorMSG = "částka nemůže být menší nebo rovna nule";
                return false;
            } 
        } else {
            $this->errorMSG = "částka není zadána";
            return false;
        }

        return true;
    }
    
}
