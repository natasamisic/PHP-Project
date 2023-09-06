<?php
	require_once("db_utils.php");

	$d = new Database();
	$errors = [];
	$messages = [];

	session_start();

	if (isset($_GET["logout"])){
		session_destroy();
	} elseif (isset($_SESSION["user"])) {
		header( "Location: movies.php" );
	}
    if(isset($_GET["accessDenied"])){
        $messages[] = "You dont have access to the requested page.";
    }

	if (isset($_GET["login-fail"])) {
		$messages[] = "Wrong username or password";
	}

    if (isset($_GET["forget-me"])) {
		setcookie("username", "", time()-1000);
		header("Location: index.php");
	}

	function outputError($errorCode) {
		global $errors;
		if (isset($errors[$errorCode])) {
			echo '<div class="error">' . $errors[$errorCode] . '</div>';
		}
	}

    $name = $username = $email = $gender = $password1 = $password2 = $birthday =  "";
	
    if (isset($_POST["registerButton"])) {
		// Setovanje promenljivih iz registracione forme
		if ($_POST["name"]) {
			$name = htmlspecialchars($_POST["name"]);
		}	
		if ($_POST["username"]) {
			$username = htmlspecialchars($_POST["username"]);
		}
		if ($_POST["email"]) {
			$email = htmlspecialchars($_POST["email"]);
		}
		if ($_POST["password1"]) {
			$password1 = $_POST["password1"];
		}	
		if ($_POST["password2"]) {
			$password2 = $_POST["password2"];
		}
		if ($_POST["birthday"]) {
			$birthday = htmlspecialchars($_POST["birthday"]);
		}
		if (isset($_POST["gender"])) {
			$gender = htmlspecialchars($_POST["gender"]);
		}

		// Validacija podataka iz registracione forme
		if (!$name) {
			$errors["name"] = "Enter full name";
		}		
		if (!$username) {
			$errors["username"] = "Enter username";
		}			
		if (!$email) {
			$errors["email"] = "Enter email";
		}		
		if (!$password1) {
			$errors["password1"] = "Enter password";
		}
		if ($password1 != $password2){
			$errors["poklapanjeLozinki"] = "Passwords are different";
		}		
		if (!$birthday) {
			$errors["birthday"] = "Enter date of birth";
		}

		if (empty($errors)) {
            $role = "user";
			$success = $d->insertUser($username, $password1, $name, $email, $birthday, $gender, $role);
            $messages[] = $success ? "Registration successful" : "Registration failed";
		}
    }
?>
<!DOCTYPE html>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- For google icons  -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
  <link rel="stylesheet" href="css/login.css">
  <title>Login form</title>
</head>
<body>
    <!-- for background -->
    <div class="background"></div>
        <?php
			if (!empty($messages)) {
                echo "<div class=\"container-mess\">";
				foreach ($messages as $message) {
					echo "<p>$message</p>";
				}
				echo "</div>";
			}
		?>
    <!-- for form container -->
    <div class="content">
        <div class="container">
            <h2>Login</h2>
            <form action="movies.php" method="post" style="margin-left: 30px">
            <div class="form-item">
                <span class="material-icons-outlined">
                account_circle
                </span>
                <input type="text" name="username" autocomplete="off" placeholder="Username" value="<?php echo isset($_COOKIE["username"]) ? $_COOKIE["username"] : "";?>">
            </div>
            <div class="form-item">
                <span class="material-icons-outlined">
                lock
                </span>
                <input type="password" name="password" placeholder="Password">
            </div>
            <div class="radio-div">
                <input type="checkbox" name="remember-me" checked> Remember my username<br> 
                <a href="?forget-me" style="color: skyblue">Forget me</a>
            </div>
            <input class="input-button" type="submit" name="loginButton" value="LOGIN"><br>
            </form>
        </div>

        <div class="container">
            <h2>Registration</h2>
            <form action="" method="post" style="margin-left: 30px">
                <label for="name" class="obavezno-polje">Full name:</label>
                <?php outputError("name");?>
                <div class="form-item">
                    <input type="text" name="name" value="<?php echo $name;?>">
                </div>

                <label for="username" class="obavezno-polje">Username:</label>
                <?php outputError("username");?>
                <div class="form-item">
                    <input type="text" name="username" value="<?php echo $username;?>">
                </div>

                <label for="email" class="obavezno-polje">Email:</label>
                <?php outputError("email");?>
                <div class="form-item">
                    <input type="text" name="email" value="<?php echo $email;?>">
                </div>

                <label for="password1" class="obavezno-polje">Password:</label>
                <?php outputError("password1");?>
                <div class="form-item">
                    <input type="password" name="password1" value="<?php echo $password1;?>">
                </div>

                <label for="password2" class="obavezno-polje">Repeat password:</label>
                <?php outputError("password2");?>
                <?php outputError("poklapanjeLozinki");?>
                <div class="form-item">
                    <input type="password" name="password2" value="<?php echo $password2;?>">
                </div>

                <label for="birthday" class="obavezno-polje">Date of birth:</label>
                <?php outputError("birthday");?>
                <div class="form-item">
                    <input type="date" name="birthday" value="<?php echo $birthday;?>">
                </div>

                <label for="gender">Gender:</label>
                <?php outputError("gender");?>
                <div class="form-item">
                    <div class="radio_div"><input type="radio" name="gender" value="male" checked>Male </div>
                    <div class="radio_div"><input type="radio" name="gender" value="female" <?php if ($gender == "female") echo 'checked'; ?>>Female </div>
                </div>
                
                <input class="input-button" type="submit" name="registerButton" value="Registrate">

            </form>
        </div>
    </div>
</body>

</html>