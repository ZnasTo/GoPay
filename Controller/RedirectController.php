<?php

class RedirectController extends Controller {
    protected $controller;

    public function execute($parameters){
        
        $url = $parameters[0];
        $partsOfPath = $this->parseURL($url);
        if (!empty($partsOfPath[0])) { // upravene
            $controllerNameHalf = $this->toCamelNotation(array_shift($partsOfPath));
            $controllerName = $controllerNameHalf . "Controller";
            if (file_exists("Controller/$controllerName.php")) {
                $this->controller = new $controllerName;
                $this->controller->execute($partsOfPath);
                $this->view = "htmlBase";
            }
            else {
               $this->redirect("error"); 
            }
        }
        else {
            $this->redirect("uvod");
        }

        $this->data["messages"] = $this->returnMessages();
    }

    private function toCamelNotation($text) {
        $text = str_replace("-", " ", $text);
        $text = ucwords($text);
        $text = str_replace(" ", "", $text);
        return $text;
    }





    public function parseURL($url) {
        $parsedURL = parse_url($url);
        $path = $parsedURL["path"];
        
        $path = ltrim($path, "/"); // odebere počáteční lomítko
        $path = trim($path); // odebere bílé znaky
        
        $explodedPath = explode("/", $path);
        
        return $explodedPath;
    }

}