<?php	
	require_once("db_utils.php");
	session_start();
	$d = new Database();
	
	$main_user = $_SESSION["user"];
    $role = $main_user[COL_USER_ROLE];
	if($role != "user"){
		header( "Location: index.php?logout&accessDenied" );
	}
	
	if (!isset($_SESSION["user"])) {
		header( "Location: index.php?logout" );
	}
	
	if (!$main_user) {
		$main_user = $_SESSION["user"];
	}
	
    if(isset($_GET["genreId"])){
        $genreId = $_GET["genreId"];
        $genre = $d->getGenre($genreId);
        $movies = $d->getMoviesByGenre($genre[COL_GENRE_NAME]);
    }

    if(isset($_GET["movieName"])){
        $movies = $d->getMoviesByName($_GET["movieName"]);
    }
?>

<html>
<head>
	<title>Movies</title>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="css/style.css">
	
</head>
<body>
	<!-- Navigacija -->
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
    <div>
    <?php
		if(count($movies) > 0){
			echo "<div style=\"margin: 50px\">";
            foreach($movies as $m){
                echo "<a href=\"movie_details.php?id={$m[COL_MOVIE_ID]}\">
                    <div class=\"card\">
                        <img class=\"movie-card\" src=\"posters/{$m[COL_MOVIE_POSTER]}\" alt=\"Movie poster\">
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
