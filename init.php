<?php
session_start();
function autoloader($className) {
    if (str_ends_with($className, "Controller")) {
        
        require "Controller/$className.php";
    }
    else {
        if(file_exists("Model/$className.php"))
        require "Model/$className.php";
    }
}
spl_autoload_register("autoloader"); 
// pokud se v kodu obejvi trida ktera jeste nebyla nactena
//spl_autoload_register se ji pokusi nacist
//tudiz nemusime vsude davat require


Db::pripoj("localhost", "root", "", "mp_gopay");
?>
