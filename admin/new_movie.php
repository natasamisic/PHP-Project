<?php
    require_once("../db_utils.php");
	session_start();
	$d = new Database();
	
	$main_user = $_SESSION["user"];
  $role = $main_user[COL_USER_ROLE];
	if($role != "admin"){
		header( "Location: ../index.php?logout&accessDenied" );
	}

	$errors = $messages = $photoErrors = [];
  $edit = false;
  $movieId = $name = $duration = $release_date = $plot = $poster = $genre = $director = $writer = $actors = "";

  if(isset($_GET["movieId"])){
    $m = $d->getMovie($_GET["movieId"]);
      $movieId = $m[COL_MOVIE_ID];
      $name = $m[COL_MOVIE_NAME];
      $duration = $m[COL_MOVIE_DURATION];
      $release_date = $m[COL_MOVIE_RELEASE_DATE];
      $plot = $m[COL_MOVIE_PLOT];
      $poster = $m[COL_MOVIE_POSTER];
      $genre = $m[COL_MOVIE_GENRE];
      $director = $m[COL_MOVIE_DIRECTOR];
      $writer = $m[COL_MOVIE_WRITER];
      $actors =$m[COL_MOVIE_ACTORS];
      $edit = true;
  }
	
	if (!isset($_SESSION["user"])) {
		header( "Location: ../index.php?logout" );
	}
	
	if (!$main_user) {
		$main_user = $_SESSION["user"];
	}

  if (isset($_POST["inputMovie"])) {
    if ($_POST["name"]) {
      $name = htmlspecialchars($_POST["name"]);
    }	
    if ($_POST["duration"]) {
      $duration = htmlspecialchars($_POST["duration"]);
    }
    if ($_POST["release_date"]) {
      $release_date = htmlspecialchars($_POST["release_date"]);
    }
    if ($_POST["plot"]) {
      $plot = htmlspecialchars($_POST["plot"]);
    }	
    if ($_POST["director"]) {
      $director = htmlspecialchars($_POST["director"]);
    }
    if ($_POST["writer"]) {
      $writer = htmlspecialchars($_POST["writer"]);
    }
    if (isset($_POST["actors"])) {
      $actors = htmlspecialchars($_POST["actors"]);
    }
    if($edit){
      $genre = htmlspecialchars($_POST["editgenre"]);
      $poster = htmlspecialchars($_POST["photo"]);
    }else{
      if (isset($_POST["poster"])) {
        processForm();
        
      }
      if (isset($_POST["chosen_genres"])) {
        foreach($_POST["chosen_genres"] as $g){
          $genre .= htmlspecialchars($g).", ";
        }
        $genre = rtrim($genre, ", ");
      }
      $poster = basename($_FILES["photo"]["name"]);
    }

    if (!$name) {
			$errors["name"] = "Enter name";
		}		
		if (!$duration) {
			$errors["duration"] = "Enter duration";
		}			
		if (!$release_date) {
			$errors["release_date"] = "Enter release_date";
		}		
		if (!$plot) {
			$errors["plot"] = "Enter plot";
		}
    if (!$poster) {
			$errors["poster"] = "Enter poster";
		}
    if (!$genre) {
			$errors["genre"] = "Choose genre";
		}
    if (!$director) {
			$errors["director"] = "Enter director";
		}
    if (!$writer) {
			$errors["writer"] = "Enter writer";
		}
    if (!$actors) {
			$errors["actors"] = "Enter actors";
		}

    if (empty($errors) && empty($photoErrors)) {
			if($edit){
        $success = $d->updateMovie($movieId, $name, $duration, $release_date, $plot, $poster, $genre, $director, $writer, $actors);
        $messages[] = $success ? "Movie was successfully updated." : "Something went wrong. Movie was not updated.";
      }else{
        $success = $d->insertMovie($name, $duration, $release_date, $plot, $poster, $genre, $director, $writer, $actors);
        $messages[] = $success ? "New movie was successfully added." : "Something went wrong. Movie was not added.";
      }
		}
  }
  function processForm() {
      global $photoErrors;
      global $poster;
      if ( isset( $_FILES["photo"] ) and $_FILES["photo"]["error"] == UPLOAD_ERR_OK ) {
        if ( $_FILES["photo"]["type"] != "image/jpeg" ) {
          $photoErrors[] =  "JPEG photos only, thanks!";
        } elseif ( move_uploaded_file( $_FILES["photo"]["tmp_name"], "images/" . basename( $_FILES["photo"]["name"] ) ) ) {
          $poster = basename($_FILES["photo"]["name"]);  
        } else {
          $photoErrors[] =  "Sorry, there was a problem uploading that photo." . $_FILES["photo"]["error"] ;
        }
      } else {
        switch( $_FILES["photo"]["error"] ) {
          case UPLOAD_ERR_INI_SIZE:
            $m = "The photo is larger than the server allows.";
            break;
          case UPLOAD_ERR_FORM_SIZE:
            $m = "The photo is larger than the script allows.";
            break;
          case UPLOAD_ERR_NO_FILE:
            $m = "No file was uploaded. Make sure you choose a file to upload.";
            break;
          default:
            $m = "Please contact your server administrator for help.";
        }
        $photoErrors[] = "Sorry, there was a problem uploading that photo. $m";
      }
    }

    function outputError($errorCode) {
      global $errors;
      if (isset($errors[$errorCode])) {
        echo '<p class="error">' . $errors[$errorCode] . '</p>';
      }
    }
?>
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Movie</title>
</head>
<body>
    <div class="background"></div>
    <ul>
        <li><a href="movies.php">All movies</a></li>
        <li><a href="new_genre.php">New genre</a></li>
        <li><a href="new_movie.php">New movie</a></li>
        <li><a href="profile.php">My profile</a></li>
        <li><a href="../index.php?logout">Logout</a></li>
    </ul>
    <div class="new-input" style="padding-top: 0">
        <?php
			if (!empty($messages)) {
        echo "<div class=\"container-mess mess\">";
				foreach ($messages as $message) {
					echo "<p>$message</p>";
				}
				echo "</div>";
			}elseif (!empty($photoErrors)) {
        echo "<div class=\"container-mess error\">";
				foreach ($photoErrors as $error) {
					echo "<p>$error</p>";
				}
				echo "</div>";
      }
		?>
        <h1>Information about the movie</h1>
        <form method="post" action="" enctype="multipart/form-data">
            <label>Name: <?php outputError("name");?></label>
            <input class="input_text" style="width:600px" type="text" name="name" value="<?php echo $name?>"><br>
            <label>Director: <?php outputError("director");?></label>
            <input class="input_text" style="width:600px" type="text" name="director" value="<?php echo $director?>"><br>
            <label>Writers: <?php outputError("writer");?></label>
            <input class="input_text" style="width:600px" type="text" name="writer" value="<?php echo $writer?>"><br>
            <label>Actors: <?php outputError("actors");?></label>
            <input class="input_text" style="width:600px" type="text" name="actors" value="<?php echo $actors?>"><br>
            <label>Duration: <?php outputError("duration");?></label>
            <input class="input_text" style="width:600px" type="text" name="duration" value="<?php echo $duration?>"><br>
            <label>Release date: <?php outputError("release_date");?></label>
            <input class="input_text" style="width:600px" type="date" name="release_date" value="<?php echo $release_date?>"><br>
            <div style="display: inline-flex; flex-flow: row wrap;">
            <label>Plot: <?php outputError("plot");?></label>
            <textarea class="input_text" style="width:600px" rows="7" cols="50" type="text" name="plot" placeholder="<?php echo $plot?>"></textarea></div><br><br>
            <label>Genres: <?php outputError("genre");?></label>
            <?php
              if($edit){
                echo "<input type=\"text\" style=\"width: 400px\" class=\"input_text\" name=\"editgenre\" value=\"$genre\"><br>
                <label>Poster: </label>
                <input class=\"input_text\" style=\"width: 400px\" type=\"text\" name=\"photo\" value=\"$poster\"/><br>";
              }else{
                echo "<div class=\"genres-table\">";
                $genres = $d->getGenres();
                if (count($genres)>0){
                    foreach($genres as $g){
                        echo "<input type=\"checkbox\" name=\"chosen_genres[]\" value=\"{$g[COL_GENRE_NAME]}\">{$g[COL_GENRE_NAME]}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                    }  
                }
                echo "</div><br>
                <label>Poster: </label>
                <input class=\"input_text\" style=\"width: 400px\" type=\"file\" name=\"photo\" id=\"photo\" value=\"$poster\"/>";
              }
              outputError("poster");
		       ?>
        <br>
            <input type="submit" name="inputMovie" value="Save">
        </form>
        
    </div>
</body>
</html>