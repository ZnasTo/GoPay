<?php
// Třída pro směrování požadavnů na stránky
class RedirectController extends Controller {

    // Proměnná pro uchování instance controlleru
    protected $controller;

    // Metoda pro provedení controlleru
    public function execute($parameters){
        $url = $parameters[0];
        $partsOfPath = $this->parseURL($url);
        
        // Kontrola, zda první část cesty není prázdná
        if (!empty($partsOfPath[0])) {
            $controllerNameHalf = $this->toCamelNotation(array_shift($partsOfPath));
            $controllerName = $controllerNameHalf . "Controller";
            
            // Kontrola jestli controller existuje
            if (file_exists("Controller/$controllerName.php")) {
                $this->controller = new $controllerName;
                $this->controller->execute($partsOfPath);

                $this->view = "htmlBase";
            }
            else {
                // Přesměrování na error, pokud controller neexistuje
                $this->redirect("error"); 
            }
        }
        else {
            // Přesměrování na uvod, pokud je první část cesty prázdná
            $this->redirect("mainpage");
        }
        // Nastavení vrácené zprávy
        $this->data["messages"] = $this->returnMessages();
    }

    // Metoda pro konverzi textu do CamelCase notace
    private function toCamelNotation($text) {
        $text = str_replace("-", " ", $text);
        $text = ucwords($text);
        $text = str_replace(" ", "", $text);

        return $text;
    }

    // Metoda pro rozparsování URL 
    public function parseURL($url) {
        $parsedURL = parse_url($url);
        $path = $parsedURL["path"];
        
        $path = ltrim($path, "/"); 
        $path = trim($path);
        
        $explodedPath = explode("/", $path);
        
        return $explodedPath;
    }

}