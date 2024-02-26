<?php
// Třída pro úvodní stránku
class MainpageController extends Controller{
    public function execute($parameters)
    {
        $this->view = "mainPage";
    }
}
