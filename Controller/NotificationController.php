<?php
// Model pro zpracování notifikací, které přicházejí z GoPay, při změně stavu objednávky
class NotificationController extends Controller
{
    public function execute($parameters) {
        $this->view = "notification";
        // Kontrola jestli je nastaven parametr id
        if (isset($_GET['id'])) {
            $paymentId = $_GET['id'];

            // Funkce upraví stav objednávky
            Notifications::updateState($paymentId);

            // Funkce odešle notifikaci jinému oddělení
            Notifications::sendNotification($paymentId);
        }
    }
    
}
