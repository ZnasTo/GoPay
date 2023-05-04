<?php

class LoginController extends Controller{
    public function execute($parameters)
    {
        $this->view = "login";

        if(isset($_POST["heslo"])){
            if(strcmp($_POST["heslo"],"th0") == 0){
                $_SESSION["sprava"] = 1;
                $redirect = new RedirectController;
                $redirect->redirect("sprava");
            }
        }

    }

}