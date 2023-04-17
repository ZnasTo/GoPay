

<?php
const DATABASE_NAME = "gopay";

require("../Model/Db.php");
class DbController  
{
    


    public static function connectToDb(){
        Db::pripoj("localhost","root","",DATABASE_NAME);

    }
    public static function getReccords(){
        return Db::dotazVsechny("
        SELECT * 
        FROM adresa
        ");
    }
}
