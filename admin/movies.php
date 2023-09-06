<?php
    require_once("../db_utils.php");
	session_start();
	$d = new Database();
	
	$main_user = $_SESSION["user"];
    $role = $main_user[COL_USER_ROLE];
	if($role != "admin"){
		header( "Location: ../index.php?logout&accessDenied" );
	}

	$errorMessage = "";
	
	if (!isset($_SESSION["user"])) {
		header( "Location: ../index.php?logout" );
	}
	
	if (!$main_user) {
		$main_user = $_SESSION["user"];
	}

?>
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Home</title>
</head>
<body>
    <div class="background"  style="min-height: 1800px"></div>
    <ul>
        <li><a href="movies.php">All movies</a></li>
        <li><a href="new_genre.php">New genre</a></li>
        <li><a href="new_movie.php">New movie</a></li>
        <li><a href="profile.php">My profile</a></li>
        <li><a href="../index.php?logout">Logout</a></li>
    </ul>
    <div style="margin-left: 50px">
        <form action="movies_by_name.php">
            Search movie by name: <input class="input-text" style="width: 350px; border-radius: 14px" type="text" name="movieName" > 
            <input type="submit" name="inputMovie" value="Search">
        </form>
    </div>
    <?php 
        $movies = $d->getAllMovies();
        if(count($movies) > 0){
            echo "<div style=\"margin: 50px\">";
            foreach($movies as $m){
                echo "<a href=\"movie_details.php?id={$m[COL_MOVIE_ID]}\">
                    <div class=\"card\">
                        <img class=\"movie-card\" src=\"../posters/{$m[COL_MOVIE_POSTER]}\" alt=\"Movie poster\">
                        <div class=\"movie-container\">
                            <h4><b>{$m[COL_MOVIE_NAME]}</b></h4> 
                            <p>{$m[COL_MOVIE_GENRE]}</p> 
                        </div>
                    </div>
                </a>";
            }
            echo "</div>";
        }
    ?>
</body>
</html>