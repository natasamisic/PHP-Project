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
		header( "Location: login.php" );
	}
	
	if (!$main_user) {
		$main_user = $_SESSION["user"];
	}
	
	function errorMessage($message) {
		global $errorMessage;
		$errorMessage = "<div class='error-msg kontejner svetlo'>$message</div>";
	}
?>

<html>
<head>
	<title><?php echo $main_user[COL_USER_NAME]; ?></title>
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
    <div class="padding">
        <div>
            <div>
                <div class="ucard user-card-full">
                    <div class="m-l-0 m-r-0">
                        <div class="bg-c-lite-green user-profile">
                            <div class="card-block">
                                <div class="m-b-25"> <img src="<?php echo "../".$main_user[COL_USER_AVATAR];?>" class="img-radius" alt="User-Profile-Image"> </div>
                                <h6 class="f-w-600"><?php echo $main_user[COL_USER_NAME];?></h6>
                                
                            </div>
                        </div>
                        <div>
                            <div class="card-block">
                                <h2 class="m-b-20 p-b-5 b-b-default f-w-600">Information</h2>
                                <div>
                                    <div>
                                        <p class="m-b-10 f-w-600">Email</p>
                                        <h6 class="text-muted f-w-400"><img src="../images/envelope.png" class="ikonica"> <?php echo $main_user[COL_USER_EMAIL];?></h6>
                                    </div>
                                    <div>
                                        <p class="m-b-10 f-w-600">Birthday</p>
                                        <h6 class="text-muted f-w-400"><img src="../images/cake_gray.png" class="ikonica"> <?php echo $main_user[COL_USER_BIRTHDAY];?></h6>
                                    </div>
                                </div>
                                <div>
                                    <div>
                                        <p class="m-b-10 f-w-600">Gender</p>
                                        <h6 class="text-muted f-w-400"><img src="../images/profile-icon.png" class="ikonica"> <?php echo $main_user[COL_USER_GENDER];?></h6>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html> 
