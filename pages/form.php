<?php include 'includes/const.php'; ?>
<?php

$show_confirmation = False;
$form_valid = True;

define("MAX_FILE_SIZE", 100000000);

$upload_feedback = array(
  'general_error' => False,
  'too_large' => False
);

$upload_file_name = NULL;
$upload_file_ext = NULL;

$form_feedback = array(
  'song_name' => 'hidden',
  'album' => 'hidden',
  'genre' => 'hidden'
);

$form_values = array(
  'song_name' => '',
  'album' => '',
);

$genre_values = array(
  "1" => '',
  "2" => '',
  "3" => '',
  "4" => ''
);

$sticky_values = array(
  'song_name' => '',
  'album' => '',
  'genre' => ''
);

if (isset($_POST['request-info'])) {
  $form_values['song_name'] = trim($_POST['song_name']);
  $form_values['album'] = trim($_POST['album']);
  $genre_values["1"] = (bool)trim($_POST['1']);
  $genre_values["2"] = (bool)trim($_POST['2']);
  $genre_values["3"] = (bool)trim($_POST['3']);
  $genre_values["4"] = (bool)trim($_POST['4']);

  if (
    $form_values['song_name'] == ''
  ) {
    $form_valid = False;
    $form_feedback['song_name'] = '';
  }
  if (
    $form_values['album'] == ''
  ) {
    $form_valid = False;
    $form_feedback['album'] = '';
  }

  if (
    $genre_values['1'] == '' && $genre_values['2'] == '' && $genre_values['3'] == ''
    && $genre_values['4'] == ''
  ) {
    $form_valid = False;
    $form_feedback['genre'] = '';
  }

  $upload = $_FILES['jpeg-file'];

  if ($upload['error'] == UPLOAD_ERR_OK) {

    $upload_file_name = basename($upload['name']);

    $upload_file_ext = strtolower(pathinfo($upload_file_name, PATHINFO_EXTENSION));

    if (!in_array($upload_file_ext, array('jpeg'))) {
      $upload_valid = False;
      $upload_feedback['general_error'] = True;
    }
  } else if (($upload['error'] == UPLOAD_ERR_INI_SIZE) || ($upload['error'] == UPLOAD_ERR_FORM_SIZE)) {
    $form_valid = False;
    $upload_feedback['too_large'] = True;
  } else {
    $form_valid = False;
    $upload_feedback['general_error'] = True;
  }

  if (
    $form_valid
  ) {

    $result =
      exec_sql_query(
        $db,
        "INSERT INTO
      songs(song_name,album) VALUES (:song_name,:album)",
        array(
          ':song_name' => $form_values['song_name'],
          ':album' => $form_values['album']
        )
      );
    $songs_id = $db->lastInsertId('id');

    foreach ($genre_values as $code => $value) {
      if ($value) {
        $result2 =
          exec_sql_query(
            $db,
            "INSERT INTO songs_tags(songs_id, tags_id) VALUES (:songs_id, :genre)",
            array(
              ':songs_id' => $songs_id,
              ':genre' => $code
            )
          );
      }
    }

    $upload_storage_path = 'public/uploads/songs/' . $songs_id   . '.' . $upload_file_ext;
    $show_confirmation = true;
    if (move_uploaded_file($upload["tmp_name"], $upload_storage_path) == False) {
      error_log("Failed to permanently store the uploaded file on the file server. Please check that the server folder exists.");
    }
    $show_confirmation = true;
  } else {
    $sticky_values['song_name'] = ($form_values['song_name'] ? 'checked' : '');
    $sticky_values['album'] = ($form_values['album'] ? 'checked' : '');
    $sticky_values['genre'] = ($form_values['genre'] ? 'checked' : '');
  }
}

$result = exec_sql_query(
  $db,
  "SELECT
  songs.id AS 'songs.id',
  songs.song_name AS 'songs.song_name' ,
songs.album AS 'songs.album',
tags.genre AS 'tags.genre'
FROM songs_tags INNER JOIN songs ON songs.id = songs_tags.songs_id
INNER JOIN tags ON (tags.id = songs_tags.tags_id ) ORDER BY songs.album DESC;"
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
  <?php if (is_user_logged_in()) { ?>

    <?php if ($show_confirmation) { ?>
      <h1> "You have sucessfully submitted the <?php echo GENRE[$form_values['genre']] ?>
        song "<?php echo $form_values['song_name'] ?>" from the album, <?php echo ALBUM[$form_values['album']] ?>, into our system!</h1>
      <img src="<?php echo $upload_storage_path ?>">
    <?php } else { ?>

      <section>

        <h1>Use the Form Below To Input A New Song</h1>
        <p class="feedback">*Please note that both the song details and its corresponding cover art must be fully completed in order for your entry to be uploaded sucessfully</p>

        <form action="/form" method="post" class="row form" enctype="multipart/form-data" novalidate>

          <div class="column">
            <h2>Upload Song Details</h2>
            <div id="feedback-classes" class="feedback <?php echo $form_feedback['song_name']; ?>">
              Please Type Song Name.
            </div>

            <div class="form-label">
              <div class="entry">
                <label for="request-song_name">Song Name</label>
                <input type="text" id="request-song_name" name="song_name" value="<?php echo $sticky_values['song_name']; ?>" />
              </div>
            </div>

            <div class="column">
              <div id="feedback-classes" class="feedback <?php echo $form_feedback['album']; ?>">
                Please Type Album Name.
              </div>

              <div class="form-label">
                <div class="entry">
                  <label for="request-album">Album</label>
                  <select id="request-album" name="album" value="<?php echo $sticky_values['album']; ?>" required>
                    <option value='' disabled selected>Select Album</option>

                    <?php foreach (ALBUM as $code => $album) { ?>
                      <option value='<?php echo $code; ?>'> <?php echo $album; ?> </option>
                    <?php } ?>
                  </select>
                </div>
              </div>

              <div class="column">

                <div id="feedback-classes" class="feedback <?php echo $form_feedback['album']; ?>">
                  Please Type Genre Name.
                </div>

                <div class="form-label">
                  <div class="entry">
                    <label for="request-genre">Genre</label>

                    <?php foreach (GENRE as $code => $genre) { ?>
                      <input type="checkbox" name="<?php echo $code; ?>" id="request-genre" <?php echo $sticky_values['genre']; ?> />
                      <label for="request-sauces"> <?php echo $genre; ?> </label>
                    <?php } ?>
                    </select>
                  </div>
                </div>

                <h2>Upload Cover Art</h2>

                <form action="/form" method="post" enctype="multipart/form-data">
                  <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MAX_FILE_SIZE; ?>">

                  <?php if ($upload_feedback['too_large']) { ?>
                    <p class="feedback">We're sorry. The file failed to upload because it was too big. Please select a file that&apos;s no larger than 10MB.</p>
                  <?php } ?>

                  <?php if ($upload_feedback['general_error']) { ?>
                    <p class="feedback">We're sorry. Something went wrong. Please select an JPEG file to upload.</p>
                  <?php } ?>

                  <div class="label-input">
                    <label class="space" for="upload-file"> Upload JPEG File:</label>
                    <input class="space" id="upload-file" type="file" name="jpeg-file" accept=".jpeg,image/jpeg+xml">
                  </div>
                  <div class="form-label">
                    <button id="request-submit" type="submit" name="request-info">
                      Add Song </button>
                  </div>
              </div>

            </div>

        </form>

      </section>

    <?php } ?>

  <?php } else { ?>
  <?php echo login_form('/form', $session_messages);
  } ?>
  </div>

</body>

</html>
