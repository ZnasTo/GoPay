<?php
// Třída pro úvodní stránku
class MainPageController extends Controller{
    public function execute($parameters)
    {
        $this->view = "mainPage";
    }
}