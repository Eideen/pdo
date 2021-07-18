<!DOCTYPE HTML>
<html>
<head>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>


<?php

/**
 * Use an HTML form to create a new entry in the
 * users table.
 *
 */

$err['anlegg'] = $lastnameErr = $emailErr = $ageErr = $locationErr = "";
$anlegg = $kameranavn = $ipaddress = $rtspurl =   $imgurl = "" ;
$_POST['rtspport'] = 554 ;
$_POST['httpport'] = 80 ;

require "../config.php";
require "../common.php";



//////////     test logic     /////////////////
if (isset($_POST['submit']))
{
  // kontroller for feil.
  $err['anlegg'] = testtekst($_POST['anlegg'],         "Anleggsnavn er påbud", "kun [0-9a-zA-Z-' ] er tiltatt, minimum 5 tegn");
  $err['kameranavn'] = testtekst($_POST['kameranavn'], "Kameranavn er påbud", "kun [-a-zA-Z0-9@:%_\+.~#?&//=] er tiltatt, minimum 5 tegn");
  $err['ipaddress'] = testIP($_POST['ipaddress'],     "IP address/hostname  er påbud", "kun [-a-zA-Z0-9@:%_\+.~#?&//=] er tiltatt, minimum 4 tegn grupper");
  $err['rtspurl'] = testURL($_POST['rtspurl'],         "RTSP url er påbud", "kun [-a-zA-Z0-9@:%_\+.~#?&//=] er tiltatt, minimum 4 tegn grupper");
  $err['rtspport'] = testPORT($_POST['rtspport'],         "RTSP port er påbud", "kun [0-9] er tiltatt, minimum 3 tegn grupper");
  $err['imgurl'] = testURL($_POST['imgurl'],         "IMG url er påbud", "kun [-a-zA-Z0-9@:%_\+.~#?&//=] er tiltatt, minimum 4 tegn grupper");
  $err['httpport'] = testPORT($_POST['httpport'],         "RTSP port er påbud", "kun [0-9] er tiltatt, minimum 3 tegn grupper");



  if ( false == ($err['anlegg'] || $err['kameranavn'] || $err['ipaddress'] || $err['imgurl'] || $err['httpport']))  {
    if (!hash_equals($_SESSION['csrf'], $_POST['csrf'])) die();

    try  {
      $connection = new PDO($dsn, $username, $password, $options);
      $imgurl = $_POST['ipaddress'] .  ":" . $_POST['httpport'] . $_POST['imgurl'] ;
//     echo "$imgurl <br>";
      $imgurl = imgproxy($key, $salt, $imgurl, 'jpg');
//      echo "$imgurl";
      $new_kamera = array(
        "anlegg"     => $_POST['anlegg'],
        "kameranavn" => $_POST['kameranavn'],
        "ipaddress"  => $_POST['ipaddress'],
        "rtspurl"    => $_POST['rtspurl'],
        "rtspport"   => $_POST['rtspport'],
        "imgurl"     => $_POST['imgurl'],
        "httpport"   => $_POST['httpport'],
        "imgproxyurl" => $imgurl

      );
      echo json_encode($new_kamera);
      echo "<br><br><br>";
      $sql = sprintf(
        "INSERT INTO %s (%s) values (%s)", "$dbtable",
        implode(", ", array_keys($new_kamera)),
        ":" . implode(", :", array_keys($new_kamera))
      );

      $statement = $connection->prepare($sql);
      $statement->execute($new_kamera);
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
  }
}
?>
<?php require "templates/header.php"; ?>


  <?php if (isset($_POST['submit']) && $statement) : ?>
    <blockquote><?php echo escape($_POST['anlegg']); ?> successfully added.</blockquote>
  <?php endif; ?>



<!--HTML -->
  <h2>Add a user</h2>
  <p><span class="error">* required field</span></p>
  <form method="post">
     <input name="csrf" type="hidden" value="<?php echo escape($_SESSION['csrf']); ?>">

    <!-- anlegg -->
    <label for="anlegg">Anlegg</label>
    <input type="text" name="anlegg" id="anlegg" value="<?php echo $_POST['anlegg'];?>">
    <span class="error">* eksemple: "Bomlafjordtunnelen" <?php echo $err['anlegg'];?></span>

    <!-- kameranavn -->
    <label for="kameranavn">Kamera name</label>
    <input type="text" name="kameranavn" id="kameranavn" value="<?php echo $_POST['kameranavn'];?>">
    <span class="error">* eksemple: "AS10-RA103" <?php echo $err['kameranavn'] ;?></span>

    <!-- ipaddress -->
    <label for="ipaddress">IP address/hostname </label>
    <input type="text" name="ipaddress" id="ipaddress" value="<?php echo $_POST['ipaddress'];?>">
    <span class="error">* <?php echo $err['ipaddress'] ;?></span>

    <!-- rtspurl -->
    <label for="age">RTSP url</label>
    <input type="text" name="rtspurl" id="rtspurl" value="<?php echo $_POST['rtspurl'];?>">
    <span class="error">* <?php echo $err['rtspurl'];?></span>

    <!-- rtspport -->
    <label for="location">RTSP port</label>
    <input type="text" name="rtspport" id="rtspport" value="<?php echo $_POST['rtspport'];?>">
    <span class="error">* <?php echo $err['rtspport'];?></span>


    <!-- imgurl -->
    <label for="location">imgurl</label>
    <input type="text" name="imgurl" id="imgurl" value="<?php echo $_POST['imgurl'];?>">
    <span class="error">* <?php echo $err['imgurl'];?></span>

    <!-- httpport -->
    <label for="location">httpport</label>
    <input type="text" name="httpport" id="httpport" value="<?php echo $_POST['httpport'];?>">
    <span class="error">* <?php echo $err['httpport'];?></span>
    <br><br>

    <input type="submit" name="submit" value="Submit">
  </form>

  <a href="index.php">Back to home</a>

<?php require "templates/footer.php"; ?>
