<?php
  // create short variable names
  $Customersqty = (int) $_POST['Customersqty'];
  $picturesqty = (int) $_POST['picturesqty'];
  $colorsqty = (int) $_POST['colorsqty'];
  $address = preg_replace('/\t|\R/',' ',$_POST['address']);
  $document_root = $_SERVER['DOCUMENT_ROOT'];
  $date = date('H:i, jS F Y');
?>
<!DOCTYPE html>
<html>
  <head>
    <title>WEDDING PHOTOGRAPHY - Order Results</title>
  </head>
  <body>
    <h1>WEDDING PHOTOGRAPHY</h1>
    <h2>Order Results</h2> 
    <?php
      echo "<p>Order processed at ".date('H:i, jS F Y')."</p>";
      echo "<p>Your order is as follows: </p>";

      $totalqty = 0;
      $totalamount = 0.00;

      define('CUSTOMERPRICE', 100);
      define('PICTUREPRICE', 10);
      define('COLORRICE', 4);

      $totalqty = $Customersqty + $picturesqty + $colorsqty;
      echo "<p>Items ordered: ".$totalqty."<br />";

      if ($totalqty == 0) {
        echo "You did not order anything on the previous page!<br />";
      } else {
        if ($Customersqty > 0) {
          echo htmlspecialchars($Customersqty).' tires<br />';
        }
        if ($picturesqty > 0) {
          echo htmlspecialchars($picturesqty).' bottles of oil<br />';
        }
        if ($colorsqty > 0) {
          echo htmlspecialchars($colorsqty).' spark plugs<br />';
        }
      }


      $totalamount = $Customersqty * CUSTOMERPRICE
                   + $picturesqty * PICTUREPRIC
                   + $colorsqty * COLORRICE;

      echo "Subtotal: $".number_format($totalamount,2)."<br />";

      $taxrate = 0.10;  // local sales tax is 10%
      $totalamount = $totalamount * (1 + $taxrate);
      echo "Total including tax: $".number_format($totalamount,2)."</p>";

      echo "<p>Address to ship to is ".htmlspecialchars($address)."</p>";

      $outputstring = $date."\t".$Customersqty." tires \t".$picturesqty." oil\t"
                      .$colorsqty." spark plugs\t\$".$totalamount
                      ."\t". $address."\n";

       // open file for appending
       @$fp = fopen("$document_root/../orders/orders.txt", 'ab');

       if (!$fp) {
         echo "<p><strong> Your order could not be processed at this time.
               Please try again later.</strong></p>";
         exit;
       }

       flock($fp, LOCK_EX);
       fwrite($fp, $outputstring, strlen($outputstring));
       flock($fp, LOCK_UN);
       fclose($fp);

       echo "<p>Order written.</p>";
    ?>
  </body>
</html>

