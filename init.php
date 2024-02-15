<?php
// Start SESSION
session_start();

// Pripojení tříd
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

// Nastavení přihlášení uživatele
if(!isset($_SESSION["prihlasen"]))
    $_SESSION["prihlasen"] = false;

// Připojení databáze
Db::connect("localhost", "root", "", "mp_gopay");

