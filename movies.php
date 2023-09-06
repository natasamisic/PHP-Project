<?php
    require_once("db_utils.php");
	session_start();
	$d = new Database();
	
	$main_user = false;
	if (isset($_POST["loginButton"])) {
		$main_user = $d->checkLogin($_POST["username"], $_POST["password"]);
		if (!$main_user) {
			header( "Location: index.php?login-fail" );
		} else {
			$_SESSION["user"] = $main_user;
			if (isset($_POST["remember-me"])) {
				setcookie("username", $main_user[COL_USER_USERNAME], time()+60*60*24*365);
			}
			$role = $main_user[COL_USER_ROLE];
			if($role != "user"){
				header( "Location: admin/movies.php" );
			}
		}
	}
    
	if (!isset($_SESSION["user"])) {
		header( "Location: index.php?logout" );
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
    <link rel="stylesheet" href="css/style.css">
    <title>Home</title>
</head>
<body>
    <div class="background" style="min-height: 1800px"></div>
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
    <div style="margin-left: 50px">
        <form action="movies_by_genre.php">
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
                        <img class=\"movie-card\" src=\"posters/{$m[COL_MOVIE_POSTER]}\" alt=\"Movie poster\">
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