<?php include 'includes/const.php'; ?>
<?php
$display = $_GET['genre'];

$sort_param = $_GET['sort'] ?? NULL;
$filter_param = $_GET['filter'] ?? NULL;
$sql_order_clause = "ORDER BY songs.album DESC;";

if (in_array($sort_param, array('album', "alpha"))) {
  if ($sort_param == 'album') {
    $sql_order_clause = "ORDER BY songs.album ASC;";
  } else if ($sort_param == 'alpha') {
    $sql_order_clause = "ORDER BY songs.song_name ASC;";
  }
}

$display = $_GET['genre'];

if (!$display) {
  $sql_select_clause = "SELECT songs.id AS 'songs.id',
  songs.song_name AS 'songs.song_name' ,
  songs.album AS 'songs.album',
  tags.genre AS 'tags.genre'
  FROM songs_tags INNER JOIN songs ON (songs.id = songs_tags.songs_id)
  INNER JOIN tags ON (tags.id = songs_tags.tags_id) GROUP BY songs.id ";

  $sql_select_query = $sql_select_clause . $sql_order_clause . ';';

  $result = exec_sql_query(
    $db,
    $sql_select_query
  );
} else {
  $sql_select_clause = "SELECT songs.id AS 'songs.id',
  songs.song_name AS 'songs.song_name' ,
  songs.album AS 'songs.album',
  tags.genre AS 'tags.genre'
  FROM songs_tags INNER JOIN songs ON (songs.id = songs_tags.songs_id)
  INNER JOIN tags ON (tags.id = songs_tags.tags_id) WHERE (tags.genre = :genre) GROUP BY songs.id
  ";
  $sql_select_query = $sql_select_clause . $sql_order_clause . ';';

  $result = exec_sql_query(
    $db,
    $sql_select_query,
    array(':genre' => $display)
  );
}

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

  <p>Welcome
    <?php if (is_user_logged_in()) { ?>
      <strong>
        <?php echo htmlspecialchars($current_user['username']); ?>
      </strong>! | <a href="<?php echo logout_url(); ?>">Log Out</a>
  </p>

<?php } else { ?>
  ! | <a href="/login">Login</a>
  <p>

  <?php } ?>
  <div class="column">
    <div class="sort">
      <strong> Sort by:</strong>
      <a class="sort_by" href="/?<?php echo http_build_query(array('sort' => 'album')); ?>">Album</a>

      <a class="sort_by" href="/?<?php echo http_build_query(array('sort' => 'alpha')); ?>">Song Name</a>
    </div>
  </div>

  <div class="row">
    <div class="filter">
      <div class="filter_by"> Filter By: </div>
      <div class="tag_group column">
        <?php foreach (GENRE as $code => $genre) {
          $file_url = '/public/images/' . $code . '.jpeg'; ?>
          <div class="tag">
            <!--Sources:
            1.jpeg: Tyler, The Creator – Call Me If You Get Lost (Columbia Records)
            2.jpeg https://benlewellyntaylor.com
            3.jpeg: http://pilerats.com/news/tyler-the-creator-au-shows/
            4.jpeg: Tyler, The Creator-->

            <img class="tag_img" src="<?php echo htmlspecialchars($file_url); ?>" alt="<?php echo $genre ?>">
            <a class="tag_link" href="/?<?php echo http_build_query(array('genre' => $code)); ?>">
              <?php echo $genre; ?></a>
            <h2>_________________________</h2>
          </div>
        <?php } ?>
        <?php if ($display) { ?>
          <div class="tag">
            <a href="/?<?php echo http_build_query(array('genre' => null)); ?>">
              Reset To All Genres</a>
          </div>
        <?php } ?>
      </div>
    </div>

    <ul class="gallery">
      <?php foreach ($records as $record) {
        $file_url = '/public/uploads/songs/' . $record['songs.id'] . '.jpeg';
      ?>
        <!-- Seed Sources:
        1.jpeg: Tyler, The Creator – Goblin (Columbia Records)
        2.jpeg: Tyler, The Creator – Wolf (Columbia Records)
        3.jpeg: https://www.defining.co/product/tyler-the-creator-x-kanye-west-x-lil-wayne-smuckers-flames-poster/
        4.jpeg: Tyler, The Creator – Igor (Columbia Records)
        5.jpeg: Tyler, The Creator – Goblin (Columbia Records)
        6.jpeg: Tyler, The Creator – Call Me If You Get Lost: The Estate Sale (Columbia Records)
        7.jpeg: Tyler, The Creator – Best Interest (Columbia Records)
        8.jpeg: Odd Future
        9.jpeg: Tyler, The Creator – Best Interest (Columbia Records)
        10.jpeg: Tyler, The Creator – Igor (Columbia Records)
        11.jpeg: https://medium.com/@hareetsingh/greatness-of-tyler-the-creators-garden-shed-d41c662bb971
        12.jpeg: Tyler, The Creator – Cherry Bomb (Columbia Records) -->

        <a href="/details?<?php echo http_build_query(array('id' => $record['songs.id'])); ?>">
          <li class="gallery-list zoom">
            <img src="<?php echo htmlspecialchars($file_url); ?>" alt="<?php echo htmlspecialchars($record['songs.song_name']); ?>">
            <div class="album-list">
              <h3>
                <?php echo htmlspecialchars($record['songs.song_name']); ?>
              </h3>
            </div>
          </li>
        </a>
      <?php } ?>

    </ul>
  </div>
  </div>
  </div>
  </div>
</body>

</html>
