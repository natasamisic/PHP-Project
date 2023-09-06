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

    $place = $date = $time = $price = $seats = "";
    if(isset($_GET["movieId"])){
        $movieId = $_GET["movieId"]; 
        $movie = $d->getMovie($movieId); 
    }
    if (isset($_POST["inputshow"])) {
        if ($_POST["place"]) {
          $place = htmlspecialchars($_POST["place"]);
        }	
        if ($_POST["date"]) {
          $date = htmlspecialchars($_POST["date"]);
        }
        if ($_POST["time"]) {
          $time = htmlspecialchars($_POST["time"]);
        }
        if ($_POST["price"]) {
          $price = htmlspecialchars($_POST["price"]);
        }	
        if ($_POST["seats"]) {
          $seats = htmlspecialchars($_POST["seats"]);
        }

        if (!$place) {
          $errors["place"] = "Enter place";
        }
        if (!$date) {
          $errors["date"] = "Enter date";
        }
        if (!$time) {
          $errors["time"] = "Enter time";
        }
        if (!$price) {
          $errors["price"] = "Enter price";
        }
        if (!$seats) {
          $errors["seats"] = "Enter number of seats";
        }
    
        if (empty($errors)) {
            $success = $d->insertShow($place, $date, $time, $price, $seats, $movieId);
            $message = $success ? "New show was successfully added." : "Something went wrong. Show was not added.";
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
    <title>New show</title>
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
        <h1>Information about the show for movie "<?php echo $movie[COL_MOVIE_NAME];?>"</h1>
        <form method="post" action="">
            <label style="width:300px">Show place: <?php outputError("place");?></label>
            <input class="input_text" type="text" name="place" value="<?php echo $place;?>"><br>
            <label style="width:300px">Show date: <?php outputError("date");?></label>
            <input class="input_text" type="date" name="date" value="<?php echo $date;?>"><br>
            <label style="width:300px">Show time: <?php outputError("time");?></label>
            <input class="input_text" type="time" name="time" value="<?php echo $time;?>"><br>
            <label style="width:300px">Ticket price: <?php outputError("price");?></label>
            <input class="input_text" type="text" name="price" value="<?php echo $price;?>"><br>
            <label style="width:300px">Number of seats available: <?php outputError("seats");?></label>
            <input class="input_text" type="text" name="seats" value="<?php echo $seats;?>"><br>
            <input type="submit" name="inputshow" value="Save">
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
</body>
</html>