<?php
require "init.php";

// Směrování požadavků na stránky
$redirect = new RedirectController;
$redirect->execute([$_SERVER["REQUEST_URI"]]);
$redirect->printView();