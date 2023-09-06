<?php 
    define("TBL_USER", "User");
    define("COL_USER_USERNAME", "username");
    define("COL_USER_ID", "id");
    define("COL_USER_PASSWORD", "password");
    define("COL_USER_NAME", "name");
    define("COL_USER_AVATAR", "avatar");
    define("COL_USER_EMAIL", "email");
    define("COL_USER_BIRTHDAY", "birthday");
    define("COL_USER_GENDER", "gender");
    define("COL_USER_ROLE", "role");

    define("TBL_GENRE", "Genre");
    define("COL_GENRE_ID", "id");
    define("COL_GENRE_NAME", "name");

    define("TBL_MOVIE", "Movie");
    define("COL_MOVIE_ID", "id");
    define("COL_MOVIE_NAME", "name");
    define("COL_MOVIE_DURATION", "duration");
    define("COL_MOVIE_RELEASE_DATE", "release_date");
    define("COL_MOVIE_PLOT", "plot");
    define("COL_MOVIE_POSTER", "poster");
    define("COL_MOVIE_GENRE", "genre");
    define("COL_MOVIE_DIRECTOR", "director");
    define("COL_MOVIE_WRITER", "writer");
    define("COL_MOVIE_ACTORS", "actors");

    define("TBL_MOVIESHOW", "MovieShow");
    define("COL_MOVIESHOW_ID", "id");
    define("COL_MOVIESHOW_PLACE", "place");
    define("COL_MOVIESHOW_TIME", "time");
    define("COL_MOVIESHOW_SHOW_DATE", "show_date");
    define("COL_MOVIESHOW_PRICE", "price");
    define("COL_MOVIESHOW_SEATS", "seats");
    define("COL_MOVIESHOW_MOVIEID", "movieId");

    define("TBL_TICKET", "Ticket");
    define("COL_TICKET_ID", "id");
    define("COL_TICKET_NUMBER_OF_SEATS", "number_of_seats");
    define("COL_TICKET_TICKET_DATE", "ticket_date");
    define("COL_TICKET_TOTAL_PRICE", "total_price");
    define("COL_TICKET_SHOWID", "showId");
    define("COL_TICKET_USERID", "userId");
?>