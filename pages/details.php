<?php include 'includes/const.php'; ?>
<?php

$display = $_GET['id'];

$result = exec_sql_query(
  $db,
  "SELECT songs.id AS 'songs.id',songs.song_name AS 'songs.song_name' ,
  songs.album AS 'songs.album',
  tags.genre AS 'tags.genre'
  FROM songs_tags INNER JOIN songs ON (songs.id = songs_tags.songs_id)
  INNER JOIN tags ON (tags.id = songs_tags.tags_id)
  WHERE (songs.id = :id) ",
  array(':id' => $display)

);

$records = $result->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" type="text/css" href="/public/styles/site.css" media="all">
  <title>Tyler, The Creator Music Catelog</title>
</head>

<body>
  <?php include 'includes/header.php'; ?>

  <?php

  foreach ($records as $record) {
    $file_url = '/public/uploads/songs/' . $display . '.jpeg';
  ?>

    <h1> <?php echo htmlspecialchars($record['songs.song_name']); ?></h1>


    <img class="detail_img" src="<?php echo htmlspecialchars($file_url); ?>" alt="<?php echo htmlspecialchars($record['song.song_name']); ?>">
    <div class="column">
      <h2>Album: <?php echo htmlspecialchars(ALBUM[$record['songs.album']]); ?></h3>

        <h2>Genre: <?php echo htmlspecialchars(GENRE[$record['tags.genre']]); ?></h3>
    </div>

  <?php } ?>


</body>

</html>
