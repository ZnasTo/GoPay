<?php
require "init.php";


$redirect = new RedirectController;
$redirect->execute([$_SERVER["REQUEST_URI"]]);
$redirect->printView();