<?php
session_start();
function autoloader($className) {
    if (str_ends_with($className, "Controller")) {
        require "Controller/$className.php";
    }
    else {
        require "Model/$className.php";
    }
}
// pokud se v kodu obejvi trida ktera jeste nebyla nactena
//spl_autoload_register se ji pokusi nacist
//tudiz nemusime vsude davat require
spl_autoload_register("autoloader"); 


Db::pripoj("localhost", "root", "", "gopay");
?>
