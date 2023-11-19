<?php
class OdhlasitController extends Controller{
    public function execute($parameters){
        $_SESSION["prihlasen"] = false;
        $this->redirect("uvod");
    }
    
}
