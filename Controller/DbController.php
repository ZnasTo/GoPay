

<?php
const DATABASE_NAME = "gopay";
class DbController extends Controller 
{
    


    public static function connectToDb(){
        Db::pripoj("localhost","root","",DATABASE_NAME);

    }
    public static function getOneReccord(){
        return Db::dotazJeden("
        SELECT * 
        FROM objednavka
        ");
    }
}
