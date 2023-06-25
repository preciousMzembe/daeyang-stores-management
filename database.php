<?php

class Database
{
    private $conn;

    public function __construct()
    {
        // start session
        session_start();

        // Database connection start 
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "daeyang_stores_management_system";
        $this->conn = mysqli_connect($servername, $username, $password, $dbname) or die("Connection failed: " . mysqli_connect_error());

        if (mysqli_connect_errno()) {
            printf("Connect failed: %s\n", mysqli_connect_error());
            exit();
        }
    }

    // login function
    function login($data)
    {
        $errors = "";

        $email = $this->clean_input($data['email']);
        $password = $this->clean_input($data['password']);

        // get user data
        $sql = "SELECT `id`, `email`, `password`, `position`, `status` FROM `users` WHERE `email` = '$email'";
        $results = mysqli_query($this->conn, $sql);
        $user = mysqli_fetch_assoc($results);

        // check email
        if (empty($user)) {
            $errors = "wrong email enterd";
            return $errors;
        }

        // check password
        if ($password != $user['password']) {
            $errors = "wrong password enterd";
            return $errors;
        }

        // check user status
        if ($user['status'] == 0) {
            $errors = "your account has beed locked, contact the adminstrator for more information.";
            return $errors;
        }

        // check user position
        if ($user['position'] != "developer" && $user['position'] != "admin" && $user['position'] != "user") {
            $errors = "you are not authorised to access any details";
            return $errors;
        }

        // check system status
        $sql = "SELECT * FROM `system` WHERE `id` = '1'";
        $results = mysqli_query($this->conn, $sql);
        $system = mysqli_fetch_assoc($results);

        if ($system['status'] == 0 && $user['position'] != "developer") {
            $errors = "the system is under maintenance, contact the adminstrator for more information.";
            return $errors;
        }

        // session for user
        $user_id = $this->clean_input($user['id']);
        $_SESSION['user_id'] = $user_id;

        // log into the system
        if ($user['status'] = "1") {
            header("location: ./system/user/index.php");
        }
    }

    // function to clean input data before saving to database
    function clean_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}
