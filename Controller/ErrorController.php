<?php
class ErrorController extends Controller{
    public function execute($parameters)
    {
        $this->view = "error";
    }
}