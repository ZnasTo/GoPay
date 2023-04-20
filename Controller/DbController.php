

<?php
const DATABASE_NAME = "gopay";

require("../Model/Db.php");
class DbController  
{
    


    public static function connectToDb(){
        Db::pripoj("localhost","root","",DATABASE_NAME);

    }
    public static function getReccordsObjednavka(){
        return Db::dotazVsechny("
        SELECT * 
        FROM objednavka INNER JOIN adresa USING(id_adresy) 
        INNER JOIN zpusobplatby USING(id_platby) 
        INNER JOIN zakaznici USING(id_zakaznika);
        ");
    }

    public static function delReccordObjednavka($idObjednavky){
        return Db::odstran("objednavka","cislo",$idObjednavky);

    }
    public static function getLastIDObjednavka(){
        return Db::dotazJeden(
            "SELECT MAX(cislo) 
            FROM objednavka;
            ");
    }
}
