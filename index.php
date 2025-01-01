<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="./index.css">
</head>

<body>
  <h1>BREN'S ALBUM CATALOGUE</h1>
  <form action="index.php" method="post" enctype="multipart/form-data">
    <table>
      <tr>
        <th>Count</th>
        <th>Album Cover</th>
        <th>Album</th>
        <th>Artist</th>
        <th>Review/Remarks</th>
        <th>Submit</th>
      </tr>
      <tr>
        <td></td>
        <td class="cover"><?php include("./components/album_image.php") ?></td>
        <td><?php include("./components/album_name.php") ?></td>
        <td><?php include("./components/artist_name.php") ?></td>
        <td><?php include("./components/remarks.php") ?></td>
        <td><button type="submit" name="add">SUBMIT</button></td>
      </tr>
      <?php
      include("database.php");
      $count = 1;

      $sql_query = "SELECT * FROM album_records";
      $result = mysqli_query($conn, $sql_query);

      if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
          echo "<tr>";
          echo "<td>{$row['id']}</td>";
          echo "<td><img style=\"width:190px; height:190px\" src=\"media/{$row['album_image']}\"</td>";
          echo "<td style=\"font-weight:bold;\">{$row['album']}</td>";
          echo "<td style=\"font-weight:bold;\">{$row['artist']}</td>";
          echo "<td><textarea name=\"review\" style=\"width:190px; height:190px; font-weight:bold; font-size:20px;\">{$row['review']}</textarea></td>";
          echo "<td colspan=\"2\"><button id=\"delete\" type=\"submit\" name=\"delete\">DELETE</button></td>";
          echo "</tr>";
        }
        //echo "<br>" . $row["album"];
      }

      mysqli_close($conn);
      ?>
    </table>
  </form>
</body>

</html>

<?php
include("database.php");

if (isset($_POST["add"])) {
  $album_image = $_FILES['image']['name'];
  $tempname = $_FILES['image']['tmp_name'];
  $folder = 'media/' . $album_image;

  move_uploaded_file($tempname, $folder);

  $album_name = trim($_POST["album"] ?? '');
  $artist_name = trim($_POST["artist"] ?? '');
  $remarks = trim($_POST["review"] ?? '');

  if (!empty($album_image) && !empty($album_name) && !empty($artist_name) && !empty($remarks)) {
    $album_image = mysqli_real_escape_string($conn, $album_image);
    $album_name = mysqli_real_escape_string($conn, $_POST["album"] ?? '');
    $artist_name = mysqli_real_escape_string($conn, $_POST["artist"] ?? '');
    $remarks = mysqli_real_escape_string($conn, $_POST["review"] ?? '');

    $sql_query = "
      INSERT INTO album_records (album_image, album, artist, review)
      VALUES ('$album_image', '$album_name', '$artist_name', '$remarks')
      ";

    if (mysqli_query($conn, $sql_query)) {
      header("Location: index.php");
      exit();
    } else {
      echo "Error: " . mysqli_error($conn);
    }
  } else {
    echo "<p style='color:red;'>All fields are required!</p>";
  }
};

if (isset($_POST["delete"])){

  $sql_delete = "
  SELECT *
  
  ";
}

$album_image = null;
$album_name = '';
$artist_name = '';
$remarks = '';

mysqli_close($conn);
?>