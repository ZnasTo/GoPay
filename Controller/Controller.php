<?php

abstract class Controller {
    protected $view = "";

    protected $data = [];

    abstract public function execute($parameters);

    public function printView(){
        extract($this->data);
        require "View/{$this->view}.phtml";
    }

    public function redirect($url){
        header("Location:/$url");
        exit;
    }

    public function addMessage($message, $type) {
        $_SESSION["messages"][] = [
            "message" => $message,
            "type" => $type
        ];    
    }

    public function returnMessages() {
        $messages = isset($_SESSION["messages"]) ? $_SESSION["message"] : [];
        unset($_SESSION["messages"]);
        return $messages;
    }


}





