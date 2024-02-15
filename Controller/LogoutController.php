<?php
// Třída pro správu odhlášení
class LogoutController extends Controller{
    public function execute($parameters){
        // Odhlásí uživatele
        $_SESSION["prihlasen"] = false;
        $this->redirect("mainpage");
    }
    
}
