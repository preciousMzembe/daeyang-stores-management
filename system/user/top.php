<?php

// database connection
require("./database.php");
$database = new Database();

// logout
if (isset($_POST['logout'])) {
    $database->logout();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../../files/icons/logo.png" />
    <link rel="stylesheet" href="./css/top.css">
    <link href="../../files/js/jquery-ui.css" rel="Stylesheet" type="text/css" />
    <title>DYUNISMS</title>
</head>

<body>

    <!-- 
    Daeyang Stores Management System.
    Auther:    Precious Mzembe.
    ID Number: BScICT/19/054.
 -->

    <section class="top_pane">
        <!-- logo -->
        <div class="logo_pane">
            <img src="../../files//icons/logo.png" alt="">
            <p>DYUNI StoresMS</p>
        </div>

        <!-- profile -->
        <div class="profile_pane">
            <div class="user_name"><?php echo $database->user_details['fname'] . " " . $database->user_details['lname'] ?></div>
            <div class="profile_image"><img src="../../files/icons/user2.png" alt=""></div>
            <div class="drop_down" onclick="show_profile_dropdown()">
                <img src="../../files/icons/down.png" alt="" class="drop_image">
                <img src="../../files/icons/menu.png" alt="" class="drop_down_optional_image">
            </div>

            <!-- dropdown_pane -->
            <div class="dropdown_pane">
                <!-- profile -->
                <div class="drop_down_profile">
                    <div class="drop_down_image"><img src="../../files/icons/user2.png" alt=""></div>
                    <div class="drop_down_name"><?php echo $database->user_details['fname'] . " " . $database->user_details['lname'] ?></div>
                </div>

                <!-- optional view -->
                <div class="dropdown_option profile_option optional" onclick="window.location='index.php'">
                    <img src="../../files/icons/dashboard.png" alt="">
                    Dashboard
                </div>

                <div class="dropdown_option profile_option optional" onclick="window.location='items.php'">
                    <img src="../../files/icons/items.png" alt="">
                    Items
                </div>

                <div class="dropdown_option profile_option optional" onclick="window.location='stockin.php'">
                    <img src="../../files/icons/stock2.png" alt="">
                    Stock In
                </div>

                <div class="dropdown_option profile_option optional" onclick="window.location='stockout.php'">
                    <img src="../../files/icons/stock2.png" alt="">
                    Stock Out
                </div>

                <div class="dropdown_option profile_option optional" onclick="window.location='reports.php'">
                    <img src="../../files/icons/stock2.png" alt="">
                    Reports
                </div>

                <div class="dropdown_option profile_option optional" onclick="window.location='analytics.php'">
                    <img src="../../files/icons/stock2.png" alt="">
                    Analytics
                </div>

                <!-- users -->
                <?php
                if ($database->user_details['position'] != "user") { ?>
                    <div class="dropdown_option profile_option optional" onclick="window.location='users.php'">
                        <img src="../../files/icons/users.png" alt="">
                        Users
                    </div>
                <?php } ?>

                <!-- system -->
                <?php
                if ($database->user_details['position'] == "developer") { ?>
                    <div class="dropdown_option profile_option optional" onclick="window.location='system.php'">
                        <img src="../../files/icons/code.png" alt="">
                        System
                    </div>
                <?php } ?>
                <!-- -------------- -->

                <div class="dropdown_option profile_option" onclick="window.location='profile.php'">
                    <img src="../../files/icons/user.png" alt="">
                    Profile
                </div>

                <div class="dropdown_option logout_option" onclick="logout();">
                    <img src="../../files/icons/logout.png" alt="">
                    Logout
                </div>
            </div>
        </div>
    </section>

    <script src="../../files/js/jquery-3.6.3.min.js"></script>
    <script type="text/javascript" src="../../files/js/jquery-ui.js"></script>
    <script>
        function show_profile_dropdown() {
            $(".drop_image").toggleClass("rotate_class");
            $(".dropdown_pane").toggle(500);
        }

        function logout() {
            let url = window.location.href;

            const form = document.createElement('form');
            form.method = "post";
            form.action = url;

            const hiddenField = document.createElement('input');
            hiddenField.type = 'hidden';
            hiddenField.name = "logout";
            hiddenField.value = "logout";

            form.appendChild(hiddenField);

            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>

</html>