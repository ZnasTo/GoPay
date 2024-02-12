<?php
// Třída pro zpracování errorů
class ErrorController extends Controller{
    public function execute($parameters)
    {
        $this->view = "error";
    }
}