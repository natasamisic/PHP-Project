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

    if(isset($_GET["movieName"])){
        $movies = $d->getMoviesByName($_GET["movieName"]);
    }
?>

<html>
<head>
	<title>Movies</title>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="../css/style.css">
	
</head>
<body>
	<!-- Navigacija -->
	<div class="background"></div>
    <ul>
        <li><a href="movies.php">All movies</a></li>
        <li><a href="new_genre.php">New genre</a></li>
        <li><a href="new_movie.php">New movie</a></li>
        <li><a href="profile.php">My profile</a></li>
        <li><a href="../index.php?logout">Logout</a></li>
    </ul>
    <div>
    <?php
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
        }else{
            echo "<div class=\"container-mess mess\" style=\"margin-top: 200px;\" >";
				echo "<p>There are no movies.</p></div>";
        }
	?>
</body>
</html> 
