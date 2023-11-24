<?php
class FormularController extends Controller {
    public function execute($parameters){
        if(!empty($_GET["money"])){
            $data["name"] = $_GET["name"] ? $_GET["name"] : NULL;
            $data["surname"] = $_GET["surname"] ? $_GET["surname"] : NULL;
            $data["email"] = $_GET["email"] ? $_GET["email"] : NULL;
            $data["phone"] = $_GET["phone"] ? $_GET["phone"] : NULL;
            $data["street"] = $_GET["street"] ? $_GET["street"] : NULL;
            $data["cp"] = $_GET["cp"] ? $_GET["cp"] : NULL;
            $data["town"] = $_GET["town"] ? $_GET["town"] : NULL;
            $data["psc"] = $_GET["psc"] ? $_GET["psc"] : NULL;
            $data["money"] = $_GET["money"] ? $_GET["money"] : NULL;

        }else {
            $this->view = "formular";
        }
    }
}