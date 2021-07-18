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


//     test logic     /////////////////
if (isset($_POST['submit']))
{
  // kontroller for feil.
  $err['imgurl'] = testURL($_POST['imgurl'],         "IMG url er påbud", "kun [-a-zA-Z0-9@:%_\+.~#?&//=] er tiltatt, minimum 4 tegn grupper");
  $output = imgproxy($key, $salt, $_POST['imgurl'], 'jpg');


}
?>
<?php require "templates/header.php"; ?>


  <?php if (isset($_POST['submit']) ) : ?>
    <blockquote><?php echo "\"$output\""; ?><br> successfully added.</blockquote>
  <?php endif; ?>



<!--HTML -->
  <h2>Gen imgproxyurl</h2>
  <p><span class="error">* required field</span></p>
  <form method="post">
     <input name="csrf" type="hidden" value="<?php echo escape($_SESSION['csrf']); ?>">

    <!-- imgurl -->
    <label for="location">imgurl</label>
    <input type="text" name="imgurl" id="imgurl" value="<?php echo $_POST['imgurl'];?>">
    <span class="error">* <?php echo $err['imgurl'];?></span>

    <input type="submit" name="submit" value="Submit">
  </form>

  <a href="index.php">Back to home</a>

<?php require "templates/footer.php"; ?>
