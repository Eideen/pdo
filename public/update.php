<?php

/**
 * List all users with a link to edit
 */

require "../config.php";
require "../common.php";

try {
  $connection = new PDO($dsn, $username, $password, $options);

  $sql = "SELECT * FROM $dbtable";

  $statement = $connection->prepare($sql);
  $statement->execute();

  $result = $statement->fetchAll();
} catch(PDOException $error) {
  echo $sql . "<br>" . $error->getMessage();
}
?>
<?php require "templates/header.php"; ?>

<h2>Update users</h2>

<table>
    <thead>
        <tr>

            <th>#</th>
            <th>Anlegg</th>
            <th>Kameranavn</th>
            <th>IP-address</th>
            <th>RTSPurl</th>
            <th>RTSPport</th>
            <th>IMGurl</th>
            <th>HTTPport</th>
            <th>imgproxyurl</th>
            <th>Date</th>
            <th>Edit</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($result as $row) : ?>
        <tr>



            <td><?php echo escape($row["id"]); ?></td>
            <td><?php echo escape($row["anlegg"]); ?></td>
            <td><?php echo escape($row["kameranavn"]); ?></td>
            <td><?php echo escape($row["ipaddress"]); ?></td>
            <td><?php echo escape($row["rtspurl"]); ?></td>
            <td><?php echo escape($row["rtspport"]); ?></td>
            <td><?php echo escape($row["imgurl"]); ?> </td>
            <td><?php echo escape($row["httpport"]); ?></td>
            <td><?php echo escape($row["imgproxyurl"]); ?></td>
            <td><?php echo escape($row["date"]); ?> </td>
            <td><a href="update-single.php?id=<?php echo escape($row["id"]); ?>">Edit</a></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<a href="index.php">Back to home</a>

<?php require "templates/footer.php"; ?>
