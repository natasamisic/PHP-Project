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
	
    if(isset($_GET["ticketId"])){
        $ticket = $d->getTicket($_GET["ticketId"]);
        $ushow = $d->getShow($ticket[COL_TICKET_SHOWID]);
        $newSeats = $ushow[COL_MOVIESHOW_SEATS] + $ticket[COL_TICKET_NUMBER_OF_SEATS];
        $updated = $d->updateShow($ushow[COL_MOVIESHOW_ID], $newSeats);
        if($updated){
            $success = $d->cancelTicket($_GET["ticketId"]);
            $message = $success ? "Reservation canceled.": "Something went wrong. Reservation was not canceled.";
        }else{
            $message = "Something went wrong. Reservation was not canceled.";
        }
    }
?>

<html>
<head>
	<title>Reservations</title>
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
		$reservations = $d->getReservations($main_user[COL_USER_ID]);
        if(count($reservations) > 0){
            echo "<div class=\"show-container\" style=\"margin-top: 100px; margin-left: 10%\">
                    <table class=\"show-table\" style=\"width: 1200px\">
                        <tr>
                        <th>Movie</th>
                        <th>Duration</th>
                        <th>Place</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Total ticket price</th>
                        <th>Number of seats</th>
                        <th>Reservation date</th>
                        <th> </th>
                     </tr>";
            foreach($reservations as $res){
                $show = $d->getShow($res[COL_TICKET_SHOWID]);
                $movie = $d->getMovie($show[COL_MOVIESHOW_MOVIEID]);
                echo "<tr>
                    <td>{$movie[COL_MOVIE_NAME]}</td>
                    <td>{$movie[COL_MOVIE_DURATION]}</td>
                    <td>{$show[COL_MOVIESHOW_PLACE]}</td>
                    <td>{$show[COL_MOVIESHOW_SHOW_DATE]}</td>
                    <td>{$show[COL_MOVIESHOW_TIME]}h</td>
                    <td>{$res[COL_TICKET_TOTAL_PRICE]}din</td>
                    <td>{$res[COL_TICKET_NUMBER_OF_SEATS]}</td>
                    <td>{$res[COL_TICKET_TICKET_DATE]}</td>
                    <form action=\"\" method=\"post\">
                    <td><a href=\"reservations.php?ticketId={$res[COL_TICKET_ID]}\">Cancel reservation</a></td>
                    </form>
                </tr>";
            }
            echo "</table>
                </div>";
        }else{
            echo "<div class=\"container-mess mess\" style=\"margin-top: 200px;\" >";
				echo "<p>You have no reservations.</p></div>";
        }
        if(!empty($message)){
            echo "<div class=\"container-mess mess\"><p>$message</p></div>";
        }
	?>
   
</body>
</html> 
