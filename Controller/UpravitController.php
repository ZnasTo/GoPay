<?php

class UpravitController extends Controller
{
    public function execute($parameters){
        $this->view = "upravit";
        if($_SESSION["sprava"]==1){
            // if(isset($_POST["neco"])){
            // print("neco");
            // $this->view = "upravit";
            // }
            
        }
        else {
            // $this->redirect("login");
        }
        print("hmmm");
    }
}
