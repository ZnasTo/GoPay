<?php
// Rodičovská třída pro všechny Controllery
abstract class Controller {

    // Vlastnost pro uchování názvu pohledu
    protected $view = "";
    // Pole dat, která budou předána do pohledu
    protected $data = [];

    // Abstraktní metoda pro provedení controlleru
    abstract public function execute($parameters);

    // Metoda pro vytisknutí pohledu
    public function printView(){
        if (!empty($this->view)) {
            extract($this->data);
            require "View/{$this->view}.phtml";
        }
    }

    // Metoda pro přesměrování na danou URL
    public function redirect($url){
        header("Location:/$url");
        exit;
    }

    // Metoda pro přidání zprávy
    public function addMessage($message, $type) {
        $_SESSION["messages"][] = [
            "message" => $message,
            "type" => $type
        ];    
    }

    // Metoda pro vrácení uložených zpráv 
    public function returnMessages() {
        $messages = isset($_SESSION["messages"]) ? $_SESSION["message"] : [];
        unset($_SESSION["messages"]);
        return $messages;
    }


}





