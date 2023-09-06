<?php
    require_once("../db_utils.php");
	session_start();
	$d = new Database();

	$main_user = $_SESSION["user"];
    $role = $main_user[COL_USER_ROLE];
	if($role != "admin"){
		header( "Location: ../index.php?logout&accessDenied" );
	}

	
	if (!isset($_SESSION["user"])) {
		header( "Location: ../index.php?logout" );
	}
	
	if (!$main_user) {
		$main_user = $_SESSION["user"];
	}

    if(isset($_GET["id"])){
        $movie = $d->getMovie($_GET["id"]);
    }
    
    if(isset($_GET["showId"])){
        $showId = $_GET["showId"];
        $ticketsdeleted = $d->deleteTicketsForShow($showId);
        if($ticketsdeleted){
            $success = $d->deleteShow($showId);
            $message = $success ? "Show was successfully deleted." : "Something went wrong. The show was not deleted.";
        }else{
            $message = "Something went wrong. The show was not deleted.";
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
    <?php 
        if(!empty($message)){
            echo "<div class=\"container-mess mess\"><p>$message</p></div>";
        }
        if(!empty($movie)){
                echo "<div class=\"movie-details\">
            <div class=\"left-column\">
              <img class=\"active\" style=\"width: 70%\" src=\"../posters/{$movie[COL_MOVIE_POSTER]}\" alt=\"Movie poster\">
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
             Edit <a href=\"new_movie.php?movieId={$movie[COL_MOVIE_ID]}\"><img src=\"../images/pencil_gray.png\" class=\"ikonica\"></a><br>
              <div class=\"movie-price\">
                <a href=\"new_show.php?movieId={$movie[COL_MOVIE_ID]}\" class=\"cart-btn\">Add new movie projection</a>
              </div>
            </div>
          </div>";
        }

        $shows = $d->getShows($movie[COL_MOVIE_ID]);
        if(count($shows) > 0){
            echo "<div class=\"show-container\">
                    <table class=\"show-table\">
                        <tr>
                        <th>Place</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Ticket price</th>
                        <th>Available seats</th>
                        <th> </th>
                     </tr>";
            foreach($shows as $show){
                echo "<tr>
                    <td>{$show[COL_MOVIESHOW_PLACE]}</td>
                    <td>{$show[COL_MOVIESHOW_SHOW_DATE]}</td>
                    <td>{$show[COL_MOVIESHOW_TIME]}h</td>
                    <td>{$show[COL_MOVIESHOW_PRICE]}din</td>
                    <td>{$show[COL_MOVIESHOW_SEATS]}</td>
                    <td><a href=\"movie_details.php?showId={$show[COL_MOVIESHOW_ID]}&id={$movie[COL_MOVIE_ID]}\">Delete</a></td>
                </tr>";
            }
            echo "</table>
                </div>";
        }else{
            echo "<div class=\"container-mess mess\">";
				echo "<p>There are no shows for this movie.</p></div>";
        }
    ?>

</body>
</html>