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
	<title><?php echo $main_user[COL_USER_NAME]; ?></title>
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
    <div class="padding">
        <div>
            <div>
                <div class="ucard user-card-full">
                    <div class="m-l-0 m-r-0">
                        <div class="bg-c-lite-green user-profile">
                            <div class="card-block">
                                <div class="m-b-25"> <img src="<?php echo $main_user[COL_USER_AVATAR];?>" class="img-radius" alt="User-Profile-Image"> </div>
                                <h6 class="f-w-600"><?php echo $main_user[COL_USER_NAME];?></h6>
                                
                            </div>
                        </div>
                        <div>
                            <div class="card-block">
                                <h2 class="m-b-20 p-b-5 b-b-default f-w-600">Information</h2>
                                <div>
                                    <div>
                                        <p class="m-b-10 f-w-600">Email</p>
                                        <h6 class="text-muted f-w-400"><img src="images/envelope.png" class="ikonica"> <?php echo $main_user[COL_USER_EMAIL];?></h6>
                                    </div>
                                    <div>
                                        <p class="m-b-10 f-w-600">Birthday</p>
                                        <h6 class="text-muted f-w-400"><img src="images/cake_gray.png" class="ikonica"> <?php echo $main_user[COL_USER_BIRTHDAY];?></h6>
                                    </div>
                                </div>
                                <div>
                                    <div>
                                        <p class="m-b-10 f-w-600">Gender</p>
                                        <h6 class="text-muted f-w-400"><img src="images/profile-icon.png" class="ikonica"> <?php echo $main_user[COL_USER_GENDER];?></h6>
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
