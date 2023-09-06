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
	
?>

<html>
<head>
	<title>Genres</title>
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
			$genres = $d->getGenres();
			if (count($genres)>0){
                echo "<ul class=\"m\">";
                foreach($genres as $genre){
                    echo "<li class=\"m\"><a href=\"movies_by_genre.php?genreId={$genre[COL_GENRE_ID]}\">{$genre[COL_GENRE_NAME]}</a></li>";
                }
                echo "</ul>";
            }
		?>
</body>
</html> 
