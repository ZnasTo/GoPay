
<?php
    require("htmlTop.php");

    require_once("../Controller/PaymentController.php");
    ?>
    <nav>
      <ul>
      <li><a href="checkout.php">checkout</a></li>
        <li><a href="../index.phtml">Hlavní Stránka</a></li>
      </ul>

    </nav>
    <?php
    $pc = new PaymentController;
    $url = $pc->getGoPayUrl("zde prijdou data");
    $cisloObjednavky = 123; // tu ulozis cislo objednavky
    // print $url;
    if(isset($_GET["id"])){
      $response = $pc->getStatus($_GET["id"]);

      $responseArray = json_decode($response,true );
      // print_r($responseArray);
      $state = $responseArray["state"];
      print "<p class='state' >" . $state . "</p>";
      if($state != "PAID"){

        
        $url = $responseArray["gw_url"];
        
        ?>
          <p class='state' >Objednávka nebyla zaplacena</p>
          <form action="<?=$url?>" method="post">
            <button name="pay" type="submit">Zaplatit</button>
          </form>
      <?php
      }
      else{
        
        header("Location: zaplaceno.phtml" . "?cisloObjednavky=" . $cisloObjednavky);
      }
      
          
    }
    else {
      ?>
      <form action="<?=$url?>" method="post">
            <button name="pay" type="submit">Zaplatit</button>
          </form>
        <?php
        }

    ?>
<?php
require("htmlBottom.html");
?>
