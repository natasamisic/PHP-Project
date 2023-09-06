<?php
    require_once("db_utils.php");
	session_start();
	$d = new Database();
	
	$main_user = $_SESSION["user"];
    $role = $main_user[COL_USER_ROLE];
	if($role != "user"){
		header( "Location: index.php?logout&accessDenied" );
	}

	$message = "";
	$seats = $resDate = $totalPrice = "";
	if (!isset($_SESSION["user"])) {
		header( "Location: index.php?logout" );
	}
	
	if (!$main_user) {
		$main_user = $_SESSION["user"];
	}

    if(isset($_GET["id"])){
        $movie = $d->getMovie($_GET["id"]);
    }

    if(isset($_POST["ticket"])){
        $seats = $_POST["seats"];
        $showId = $_POST["showId"];
        $resDate = date("d.m.Y H:i:s");
        $oneprice = $_POST["price"];
        $totalPrice = $seats * $oneprice;
        $resShow = $d->getShow($showId);
        $newSeats = $resShow[COL_MOVIESHOW_SEATS] - $seats;
        if($newSeats > 0){
            $updated = $d->updateShow($showId, $newSeats);
            if($updated){
                $success = $d->insertTicket($showId, $seats, $resDate, $totalPrice, $main_user[COL_USER_ID]);
                $message = $success ? "Reservation successful." : "Something went wrong. Reservation was not successful.";
            }
        }else{
            $message = "There are no av available seats for the show.";
        }
    }
    
?>
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Movie</title>
</head>
<body>
    <div class="background"></div>
    <div class="navbar">
        <a href="movies.php" style="font-weight: bold">Home</a>
        <a href="genres.php" style="font-weight: bold">Genres</a>
        <div class="dropdown">
			<p style="float: left"><?php echo $main_user[COL_USER_USERNAME];?></p>
            <button class="dropbtn"><img src="<?php echo $main_user[COL_USER_AVATAR];?>"></button>
            <div class="dropdown-content">
                <a href="profile.php">My profile</a>
                <a href="reservations.php">My reservations</a>
                <a href="index.php?logout" id="logout-button">Logout</a>
            </div>
        </div> 
    </div>
    <?php 
        if(!empty($message)){
            echo "<div class=\"container-mess mess\"><p>$message</p></div>";
        }
        if(!empty($movie)){
                echo "<div class=\"movie-details\">
            <div class=\"left-column\">
              <img class=\"active\" style=\"width: 70%\" src=\"posters/{$movie[COL_MOVIE_POSTER]}\" alt=\"Movie poster\">
            </div>
           
            <div class=\"right-column\">
              <div class=\"product-description\">
                <h1>{$movie[COL_MOVIE_NAME]}</h1>
                <p>{$movie[COL_MOVIE_PLOT]}</p>
              </div>
           
             <div class=\"movie-description\">
                 <span>Director:</span> {$movie[COL_MOVIE_DIRECTOR]}
             </div>
             <div class=\"movie-description\">
                 <span>Writers:</span> {$movie[COL_MOVIE_WRITER]}
             </div>
             <div class=\"movie-description\">
                 <span>Release date:</span> {$movie[COL_MOVIE_RELEASE_DATE]}
             </div>
             <div class=\"movie-description\">
                 <span>Duration:</span> {$movie[COL_MOVIE_DURATION]}
             </div>
             <div class=\"movie-description\">
                 <span>Genres:</span> {$movie[COL_MOVIE_GENRE]}
             </div>
             <div class=\"movie-description\">
                 <span>Actors:</span> {$movie[COL_MOVIE_ACTORS]}
             </div>
            </div>
          </div>";
        }

        $shows = $d->getShows($movie[COL_MOVIE_ID]);
        if(count($shows) > 0){
            echo "<div class=\"show-container\" style=\"margin-top: 100px\">
                    <table class=\"show-table\">
                        <tr>
                        <th>Place</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Ticket price</th>
                        <th>Available seats</th>
                        <th>Number of seats you want</th>
                        <th> </th>
                     </tr>";
            foreach($shows as $show){
                echo "<tr>
                    <td>{$show[COL_MOVIESHOW_PLACE]}</td>
                    <td>{$show[COL_MOVIESHOW_SHOW_DATE]}</td>
                    <td>{$show[COL_MOVIESHOW_TIME]}</td>
                    <td>{$show[COL_MOVIESHOW_PRICE]}din</td>
                    <td>{$show[COL_MOVIESHOW_SEATS]}</td>
                    <form action=\"\" method=\"post\">
                    <td><input class=\"input-text\" style=\"width: 50px\" type=\"number\" name=\"seats\"></td>
                    <td><input type=\"submit\" name=\"ticket\" value=\"Make a reservation\">
                    <input type=\"hidden\" name=\"showId\" value=\"{$show[COL_MOVIESHOW_ID]}\">
                    <input type=\"hidden\" name=\"price\" value=\"{$show[COL_MOVIESHOW_PRICE]}\"></td>
                    </form>
                </tr>";
            }
            echo "</table>
                </div>";
        }else{
            echo "<div class=\"container-mess mess\" style=\"margin-top: 200px;\" >";
				echo "<p>There are no shows for this movie.</p></div>";
        }
    ?>
</body>
</html>