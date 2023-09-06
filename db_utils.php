<?php
require_once("constants.php");

class Database{
    private $hashing_salt = "dsaf7493^&$(#@Kjh";

    private $conn;

    public function __construct($configFile = "config.ini"){
        if ($config = parse_ini_file($configFile)) {
            $host = $config["host"];
            $database = $config["database"];
            $user = $config["user"];
            $password = $config["password"];
            $this->conn = new PDO("mysql:host=$host;dbname=$database", $user, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
    }

    public function __destruct(){
        $this->conn = null;
    }

    function insertUser($username, $password, $name, $email, $birthday, $gender, $role){
        try {
            $sql_existing_user = "SELECT * FROM " . TBL_USER . " WHERE " . COL_USER_USERNAME . "= :username";
            $st = $this->conn->prepare($sql_existing_user);
            $st->bindValue(":username", $username, PDO::PARAM_STR);
            $st->execute();
            if ($st->fetch()) {
                return false;
            }
            
            $hashed_password = crypt($password, $this->hashing_salt);
            $avatar = $gender == "female" ? "images/female_avatar.png" : "images/male_avatar.png";

            $sql_insert = "INSERT INTO " . TBL_USER . " (".COL_USER_USERNAME.","
                                                          .COL_USER_PASSWORD.","
                                                          .COL_USER_NAME.","
                                                          .COL_USER_AVATAR.","
                                                          .COL_USER_EMAIL.","
                                                          .COL_USER_BIRTHDAY.","
                                                          .COL_USER_GENDER.","
                                                          .COL_USER_ROLE.")"
                        ." VALUES (:username, :password, :name, :avatar, :email, :birthday, :gender, :role)";

            $st = $this->conn->prepare($sql_insert);
            $st->bindValue("username", $username, PDO::PARAM_STR);
            $st->bindValue("password", $hashed_password, PDO::PARAM_STR);
            $st->bindValue("name", $name, PDO::PARAM_STR);
            $st->bindValue("avatar", $avatar, PDO::PARAM_STR);
            $st->bindValue("email", $email, PDO::PARAM_STR);
            $st->bindValue("birthday", $birthday, PDO::PARAM_STR);
            $st->bindValue("gender", $gender, PDO::PARAM_STR);
            $st->bindValue("role", $role, PDO::PARAM_STR);
            
            return $st->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function insertGenre($genre){
        try {
            $sql = "INSERT INTO " . TBL_GENRE . " (".COL_GENRE_NAME.")"."VALUES (:genre)";
            $st = $this->conn->prepare($sql);
            $st->bindValue("genre", $genre, PDO::PARAM_STR);
            return $st->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    function insertMovie($name, $duration, $release_date, $plot, $poster, $genre, $director, $writer, $actors){
        try {
            $sql_insert = "INSERT INTO " . TBL_MOVIE . " (".COL_MOVIE_NAME.","
                                                          .COL_MOVIE_DURATION.","
                                                          .COL_MOVIE_RELEASE_DATE.","
                                                          .COL_MOVIE_PLOT.","
                                                          .COL_MOVIE_POSTER.","
                                                          .COL_MOVIE_GENRE.","
                                                          .COL_MOVIE_DIRECTOR.","
                                                          .COL_MOVIE_WRITER.","
                                                          .COL_MOVIE_ACTORS.")"
                        ." VALUES (:name, :duration, :release_date, :plot, :poster, :genre, :director, :writer, :actors)";
            $st = $this->conn->prepare($sql_insert);
            $st->bindValue("name", $name, PDO::PARAM_STR);
            $st->bindValue("duration", $duration, PDO::PARAM_STR);
            $st->bindValue("release_date", $release_date, PDO::PARAM_STR);
            $st->bindValue("plot", $plot, PDO::PARAM_STR);
            $st->bindValue("poster", $poster, PDO::PARAM_STR);
            $st->bindValue("genre", $genre, PDO::PARAM_STR);
            $st->bindValue("director", $director, PDO::PARAM_STR);
            $st->bindValue("writer", $writer, PDO::PARAM_STR);
            $st->bindValue("actors", $actors, PDO::PARAM_STR);
            
            return $st->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    function insertShow($place, $date, $time, $price, $seats, $movieId){
        try {
            $sql_insert = "INSERT INTO " . TBL_MOVIESHOW . " (".COL_MOVIESHOW_PLACE.","
                                                          .COL_MOVIESHOW_SHOW_DATE.","
                                                          .COL_MOVIESHOW_TIME.","
                                                          .COL_MOVIESHOW_PRICE.","
                                                          .COL_MOVIESHOW_SEATS.","
                                                          .COL_MOVIESHOW_MOVIEID.")"
                        ." VALUES (:place, :date, :time, :price, :seats, :movieId)";
            $st = $this->conn->prepare($sql_insert);
            $st->bindValue("place", $place, PDO::PARAM_STR);
            $st->bindValue("date", $date, PDO::PARAM_STR);
            $st->bindValue("time", $time, PDO::PARAM_STR);
            $st->bindValue("price", $price, PDO::PARAM_STR);
            $st->bindValue("seats", $seats, PDO::PARAM_STR);
            $st->bindValue("movieId", $movieId, PDO::PARAM_STR);
            
            return $st->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    function insertTicket($showId, $seats, $resDate, $price, $userId){
        try {
            $sql_insert = "INSERT INTO " . TBL_TICKET . " (".COL_TICKET_NUMBER_OF_SEATS.","
                                                          .COL_TICKET_TICKET_DATE.","
                                                          .COL_TICKET_TOTAL_PRICE.","
                                                          .COL_TICKET_SHOWID.","
                                                          .COL_TICKET_USERID.")"
                        ." VALUES (:seats, :resDate, :price, :showId, :userId)";
            $st = $this->conn->prepare($sql_insert);
            $st->bindValue("seats", $seats, PDO::PARAM_STR);
            $st->bindValue("resDate", $resDate, PDO::PARAM_STR);
            $st->bindValue("price", $price, PDO::PARAM_STR);
            $st->bindValue("showId", $showId, PDO::PARAM_STR);
            $st->bindValue("userId", $userId, PDO::PARAM_STR);
            
            return $st->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    function updateMovie($movieId, $name, $duration, $release_date, $plot, $poster, $genre, $director, $writer, $actors){
        try {
            $sql = "UPDATE " . TBL_MOVIE . " SET ".COL_MOVIE_NAME."= :name,"
                                                          .COL_MOVIE_DURATION."= :duration,"
                                                          .COL_MOVIE_RELEASE_DATE."= :release_date,"
                                                          .COL_MOVIE_PLOT."= :plot,"
                                                          .COL_MOVIE_POSTER."= :poster,"
                                                          .COL_MOVIE_GENRE."= :genre,"
                                                          .COL_MOVIE_DIRECTOR."= :director,"
                                                          .COL_MOVIE_WRITER."= :writer,"
                                                          .COL_MOVIE_ACTORS."= :actors WHERE ".COL_MOVIE_ID."= :id";
            $st = $this->conn->prepare($sql);
            $st->bindValue("name", $name, PDO::PARAM_STR);
            $st->bindValue("duration", $duration, PDO::PARAM_STR);
            $st->bindValue("release_date", $release_date, PDO::PARAM_STR);
            $st->bindValue("plot", $plot, PDO::PARAM_STR);
            $st->bindValue("poster", $poster, PDO::PARAM_STR);
            $st->bindValue("genre", $genre, PDO::PARAM_STR);
            $st->bindValue("director", $director, PDO::PARAM_STR);
            $st->bindValue("writer", $writer, PDO::PARAM_STR);
            $st->bindValue("actors", $actors, PDO::PARAM_STR);
            $st->bindValue("id", $movieId, PDO::PARAM_STR);
            
            return $st->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getAllMovies(){
        try {
            $sql = "SELECT * FROM " . TBL_MOVIE;
            $st = $this->conn->prepare($sql);
            $st->execute();
            return $st->fetchAll();
        } catch (PDOException $e) {
            return array();
        }
    }

    public function getMovie($movieId){
        try {
            $sql = "SELECT * FROM " . TBL_MOVIE . " WHERE " . COL_MOVIE_ID . "=:movie";
            $st = $this->conn->prepare($sql);
            $st->bindValue("movie", $movieId, PDO::PARAM_INT);
            $st->execute();
            return $st->fetch();
        } catch (PDOException $e) {
            return array();
        }
    }

    public function getShows($movieId){
        try {
            $sql = "SELECT * FROM " . TBL_MOVIESHOW . " WHERE " . COL_MOVIESHOW_MOVIEID . "=:movie";
            $st = $this->conn->prepare($sql);
            $st->bindValue("movie", $movieId, PDO::PARAM_INT);
            $st->execute();
            return $st->fetchAll();
        } catch (PDOException $e) {
            return array();
        }
    }

    public function getShow($showId){
        try {
            $sql = "SELECT * FROM " . TBL_MOVIESHOW . " WHERE " . COL_MOVIESHOW_ID . "=:show";
            $st = $this->conn->prepare($sql);
            $st->bindValue("show", $showId, PDO::PARAM_INT);
            $st->execute();
            return $st->fetch();
        } catch (PDOException $e) {
            return array();
        }
    }

    public function getTicket($ticketId){
        try {
            $sql = "SELECT * FROM " . TBL_TICKET . " WHERE " . COL_TICKET_ID . "=:id";
            $st = $this->conn->prepare($sql);
            $st->bindValue("id", $ticketId, PDO::PARAM_INT);
            $st->execute();
            return $st->fetch();
        } catch (PDOException $e) {
            return array();
        }
    }

    public function getGenre($genreId){
        try {
            $sql = "SELECT * FROM " . TBL_GENRE . " WHERE " . COL_GENRE_ID . "=:id";
            $st = $this->conn->prepare($sql);
            $st->bindValue("id", $genreId, PDO::PARAM_INT);
            $st->execute();
            return $st->fetch();
        } catch (PDOException $e) {
            return array();
        }
    }

    public function getMoviesByGenre($genre){
        try {
            $sql = "SELECT * FROM " . TBL_MOVIE . " WHERE INSTR(" . COL_MOVIE_GENRE . ", :genre) > 0";
            $st = $this->conn->prepare($sql);
            $st->bindValue("genre", $genre, PDO::PARAM_STR);
            $st->execute();
            return $st->fetchAll();
        } catch (PDOException $e) {
            return array();
        }
    }

    public function getMoviesByName($name){
        try {
            $sql = "SELECT * FROM " . TBL_MOVIE . " WHERE INSTR(" . COL_MOVIE_NAME . ", :name) > 0";
            $st = $this->conn->prepare($sql);
            $st->bindValue("name", $name, PDO::PARAM_STR);
            $st->execute();
            return $st->fetchAll();
        } catch (PDOException $e) {
            return array();
        }
    }

    public function getReservations($userId){
        try {
            $sql = "SELECT * FROM " . TBL_TICKET . " WHERE " . COL_TICKET_USERID . "=:id";
            $st = $this->conn->prepare($sql);
            $st->bindValue("id", $userId, PDO::PARAM_INT);
            $st->execute();
            return $st->fetchAll();
        } catch (PDOException $e) {
            return array();
        }
    }

    function updateShow($showId, $seats){
        try {
            $sql = "UPDATE " . TBL_MOVIESHOW . " SET ".COL_MOVIESHOW_SEATS."= :seats WHERE ".COL_MOVIESHOW_ID."= :id";
            $st = $this->conn->prepare($sql);
            $st->bindValue("seats", $seats, PDO::PARAM_STR);
            $st->bindValue("id", $showId, PDO::PARAM_STR);
            
            return $st->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    function deleteShow($showId){
        try {
            $sql = "DELETE FROM " . TBL_MOVIESHOW . " WHERE ".COL_MOVIESHOW_ID."= :id";
            $st = $this->conn->prepare($sql);
            $st->bindValue("id", $showId, PDO::PARAM_STR);
            
            return $st->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function deleteTicketsForShow($showId){
        try {
            $sql = "DELETE FROM " . TBL_TICKET . " WHERE ".COL_TICKET_SHOWID."= :id";
            $st = $this->conn->prepare($sql);
            $st->bindValue("id", $showId, PDO::PARAM_STR);
            
            return $st->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    function cancelTicket($ticketId){
        try {
            $sql = "DELETE FROM " . TBL_TICKET . " WHERE ".COL_TICKET_ID."= :id";
            $st = $this->conn->prepare($sql);
            $st->bindValue("id", $ticketId, PDO::PARAM_STR);
            
            return $st->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function checkLogin($username, $password){
        try {
            $hashed_password = crypt($password, $this->hashing_salt);
            $sql = "SELECT * FROM " . TBL_USER . " WHERE " . COL_USER_USERNAME . "=:username and " . COL_USER_PASSWORD . "=:password";
            $st = $this->conn->prepare($sql);
            $st->bindValue("username", $username, PDO::PARAM_STR);
            $st->bindValue("password", $hashed_password, PDO::PARAM_STR);
            $st->execute();
            return $st->fetch();
        } catch (PDOException $e) {
            return null;
        }
    }

    public function getGenres(){
        try {
            $sql = "SELECT * FROM " . TBL_GENRE;
            $st = $this->conn->prepare($sql);
            $st->execute();
            return $st->fetchAll();
        } catch (PDOException $e) {
            return array();
        }
    }
}