<?php
require "init.php";


 $redirect = new RedirectController;
 print($_SERVER["REQUEST_URI"]);
$x = $redirect->parseURL($_SERVER["REQUEST_URI"]);
// print $x[2]; 
$redirect->execute([$_SERVER["REQUEST_URI"]]);
$redirect->printView();