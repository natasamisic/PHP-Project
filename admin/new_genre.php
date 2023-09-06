<?php
    require_once("../db_utils.php");
	session_start();
	$d = new Database();
	
	$main_user = $_SESSION["user"];
    $role = $main_user[COL_USER_ROLE];
	if($role != "admin"){
		header( "Location: ../index.php?logout&accessDenied" );
	}

	$message = "";
    $errors = [];
	
	if (!isset($_SESSION["user"])) {
		header( "Location: ../index.php?logout" );
	}
	if (!$main_user) {
		$main_user = $_SESSION["user"];
	}

    $genre = "";
    if (isset($_POST["inputGenre"])) {
        if ($_POST["genre"]) {
            $genre = htmlspecialchars($_POST["genre"]);
          }	
        if(!$genre){
            $errors["genre"] = "Enter genre name";
        }
        if(empty($errors)){
            $added = $d->insertGenre($genre);
            $message= $added ? "New genre was successfully added." : "Something went wrong. The genre was not added.";
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
    <title>New genre</title>
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
    <div class="new-input">
        <form method="post" action="">
            <label style="width:300px">Enter the name of the new genre: </label>
            <input class="input_text" type="text" name="genre" value="<?php echo $genre;?>">
            <input type="submit" name="inputGenre" value="Save"><?php outputError("genre");?>
        </form>
        <?php
			if (!empty($message)) {
                echo "<div class=\"container-mess mess\">";
				echo "<p>$message</p>";
				echo "</div>";
			}
            echo "<br>";
		?>
    </div>
    <div>
        <?php
			$genres = $d->getGenres();
			if (count($genres)>0){
                echo "<ul class=\"a\">";
                foreach($genres as $genre){
                    echo "<li class=\"a\">{$genre[COL_GENRE_NAME]}</li>";
                }
                echo "</ul>";
            }
		?>
    </div>
</body>
</html>