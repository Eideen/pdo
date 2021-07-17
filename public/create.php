<?php

/**
 * Use an HTML form to create a new entry in the
 * users table.
 *
 */
$firstname = $lastname = $email = $age = $location = "";
$firstnameErr = $lastnameErr = $emailErr = $ageErr = $locationErr = "";

require "../config.php";
require "../common.php";

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

if (isset($_POST['submit'])) {

  if (empty($_POST['firstname'])) {
    $firstnameErr = "firstname is required";
  } else {
    // save value to variable, to repost
    $firstname = test_input($_POST["firstname"]);
    // check if name only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z-' ]*$/",$firstname)) {
      $firstnameErr = "Only letters and white space allowed";
     // echo escape($_POST['firstname']);
    }
  }
  if (empty($_POST['lastname'])) {
    $lastnameErr = "lastname is required";
  } else {
    // save value to variable, to repost
    $lastname = test_input($_POST["lastname"]);
    // check if name only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z-' ]*$/",$lastname)) {
      $lastnameErr = "Only letters and white space allowed";
     // echo escape($_POST['firstname']);
    }
  }
  if (empty($_POST["email"])) {
    $emailErr = "Email is required";
  } else {
    $email = test_input($_POST["email"]);
    // check if e-mail address is well-formed
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $emailErr = "Invalid email format";
    }
  }
  if (empty($_POST['age'])) {
    $ageErr = "age is required";
  } else {
    // save value to variable, to repost
    $age = test_input($_POST["age"]);
    // check if name only contains letters and whitespace
    if (!preg_match("/^[1-9][0-9]*$/",$age)) {
      $ageErr = "Only vaulves between 1-99 ";
     // echo escape($_POST['firstname']);
    }
  }
  /// location
  if (empty($_POST['location'])) {
    $locationErr = "location is required";
  } else {
    // save value to variable, to repost
    $location = test_input($_POST["location"]);
    // check if name only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z-' ]*$/",$location)) {
      $locationErr = "Only letters and white space allowed";
     // echo escape($_POST['firstname']);
    }
  }

  if ( false == ($firstnameErr || $lastnameErr || $emailErr || $ageErr || $locationErr))  {
    if (!hash_equals($_SESSION['csrf'], $_POST['csrf'])) die();

    try  {

      $connection = new PDO($dsn, $username, $password, $options);

      $new_user = array(
        "firstname" => $_POST['firstname'],
        "lastname"  => $_POST['lastname'],
        "email"     => $_POST['email'],
        "age"       => $_POST['age'],
        "location"  => $_POST['location']
      );

      $sql = sprintf(
        "INSERT INTO %s (%s) values (%s)",
        "users",
        implode(", ", array_keys($new_user)),
        ":" . implode(", :", array_keys($new_user))
      );

      $statement = $connection->prepare($sql);
      $statement->execute($new_user);
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
  }
}
?>
<?php require "templates/header.php"; ?>


  <?php if (isset($_POST['submit']) && $statement) : ?>
    <blockquote><?php echo escape($_POST['firstname']); ?> successfully added.</blockquote>
  <?php endif; ?>

<!--HTML -->
  <h2>Add a user</h2>

<p><span class="error">* required field</span></p>
  <form method="post">
    <input name="csrf" type="hidden" value="<?php echo escape($_SESSION['csrf']); ?>">

    <!-- Firstname -->
    <label for="firstname">First Name</label>
    <input type="text" name="firstname" id="firstname" value="<?php echo $firstname;?>">
    <span class="error">* <?php echo $firstnameErr;?></span>

    <!-- Lasttname -->
    <label for="lastname">Last Name</label>
    <input type="text" name="lastname" id="lastname" value="<?php echo $lastname;?>">
    <span class="error">* <?php echo $lastnameErr;?></span>

    <!-- epost -->
    <label for="email">Email Address</label>
    <input type="text" name="email" id="email" value="<?php echo $email;?>">
    <span class="error">* <?php echo $emailErr;?></span>

    <!-- age -->
    <label for="age">Age</label>
    <input type="text" name="age" id="age" value="<?php echo $age;?>">
    <span class="error">* <?php echo $ageErr;?></span>

    <!-- Location -->
    <label for="location">Location</label>
    <input type="text" name="location" id="location" value="<?php echo $location;?>">
    <span class="error">* <?php echo $locationErr;?></span>
      <br><br>
    <input type="submit" name="submit" value="Submit">
  </form>

  <a href="index.php">Back to home</a>

<?php require "templates/footer.php"; ?>
