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
    <form action="index.php" method="post" enctype="multipart/form-data"> <!-- Set Table as a Form with method POST-->
    <table>
      <tr>
        <th>Album Cover</th>
        <th>Album</th>
        <th>Artist</th>
        <th>Review/Remarks</th>
        <th>Submit</th>
      </tr>
        <tr><!--All Components import from external file using the include php syntax-->
        <td class="cover"><?php include("./components/album_image.php") ?></td>
        <td><?php include("./components/album_name.php") ?></td>
        <td><?php include("./components/artist_name.php") ?></td>
        <td><?php include("./components/remarks.php") ?></td>
        <td><button type="submit" name="add">SUBMIT</button></td>
      </tr>
      <?php
      include("database.php"); //This is to initialize and connect the database to the project

      $sql_query = "SELECT * FROM album_records";    // Query all the data with * from the album_records table
      $result = mysqli_query($conn, $sql_query);     // mysqli_query needs two arguments, the connection and the query

      if (mysqli_num_rows($result) > 0) {            // This checks if the number of rows on the database is not empty
        while ($row = mysqli_fetch_assoc($result)) { // While the mysqli_fetch_assoc does not return an empty data
          echo "<tr>";
          echo "<td><img style=\"width:190px; height:190px\" src=\"media/{$row['album_image']}\"</td>";
          echo "<td style=\"font-weight:bold;\">{$row['album']}</td>";
          echo "<td style=\"font-weight:bold;\">{$row['artist']}</td>";
          echo "<td style=\"font-weight:bold;width:190px;\">{$row['review']}</td>";
          echo "<td colspan=\"2\"><button id=\"delete\" type=\"submit\" name=\"delete\" value=\"{$row['id']}\">DELETE</button></td>";
          echo "</tr>";
        } //Do all of this
      }

      mysqli_close($conn); //After fetching the data and rendering it on the table, close the sql.
      ?>
    </table>
  </form>
</body>

</html>

<?php               //This php section is for fetching the input data from the user using the POST table method
include("database.php"); // This is for starting the database

if (isset($_POST["add"])) {               //isset is for checking if the button "add" was clicked
  $album_image = $_FILES['image']['name'];  //This is for adding the image name to the media folder
  $tempname = $_FILES['image']['tmp_name']; //This is for adding the image name to the media folder
  $folder = 'media/' . $album_image;        //This is for adding the image name to the media folder

  move_uploaded_file($tempname, $folder); //move_uploaded_file needs two arguments, the file name, and the folder name using
                                          //the syntax from above

  $album_name = trim($_POST["album"] ?? '');   //This will get the input from the POST with the "album" id and trim it, but returns an empty string if nothing inside
  $artist_name = trim($_POST["artist"] ?? ''); //This will get the input from the POST with the "album" id and trim it, but returns an empty string if nothing inside
  $remarks = trim($_POST["review"] ?? '');     //This will get the input from the POST with the "album" id and trim it, but returns an empty string if nothing inside

  if (!empty($album_image) && !empty($album_name) && !empty($artist_name) && !empty($remarks)) { // This checks if all the input areas are filled with data
    $album_image = mysqli_real_escape_string($conn, $album_image);           //mysqli_real_escape_string is for escaping "|" special characters for safety
    $album_name = mysqli_real_escape_string($conn, $_POST["album"] ?? '');   //mysqli_real_escape_string is for escaping "|" special characters for safety
    $artist_name = mysqli_real_escape_string($conn, $_POST["artist"] ?? ''); //mysqli_real_escape_string is for escaping "|" special characters for safety
    $remarks = mysqli_real_escape_string($conn, $_POST["review"] ?? '');     //mysqli_real_escape_string is for escaping "|" special characters for safety

    $sql_query = " 
      INSERT INTO album_records (album_image, album, artist, review)
      VALUES ('$album_image', '$album_name', '$artist_name', '$remarks')
      ";//This is the query to insert the fetched data on to the data set which the HTML will render later

    if (mysqli_query($conn, $sql_query)) { // The mysqli_query function needs two arguments, the conn from the database and the query which is above
      header("Location: index.php");       // This kinds of refreshes the website by sending it back to index.php to refresh everything
      exit();
    } else {
      echo "Error: " . mysqli_error($conn);// Error catching
    }
  } else { // If at least one is not filled with input on the table
    echo "<p style='color:red;'>All fields are required!</p>";
  }
};

if (isset($_POST["delete"])) {                                      // For the delete functionability
  $album_id = $_POST["delete"];                                     // Get the album id to delete
  $sql_delete = "DELETE FROM album_records WHERE id = '$album_id'"; //This is the query to delete based on the id

  if (mysqli_query($conn, $sql_delete)) { // The mysqli_query function needs two arguments, the conn from the database and the query which is above
    header("Location: index.php"); // Redirect after deleting
    exit();
  } else {
    echo "Error deleting record: " . mysqli_error($conn);
  }
}

$album_image = null;
$album_name = '';
$artist_name = '';
$remarks = '';

mysqli_close($conn);
?>
