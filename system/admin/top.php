<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../../files/icons/logo.png" />
    <link rel="stylesheet" href="./css/top.css">
    <link href="../../files/js/jquery-ui.css" rel="Stylesheet" type="text/css" />
    <title>DSMS</title>
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
            DaeyangStoresMS
        </div>

        <!-- profile -->
        <div class="profile_pane">
            <div class="user_name">Precious Mzembe</div>
            <div class="profile_image"><img src="../../files/icons/user2.png" alt=""></div>
            <div class="drop_down" onclick="show_profile_dropdown()"><img src="../../files/icons/down.png" alt=""></div>

            <!-- dropdown_pane -->
            <div class="dropdown_pane">
                <!-- profile -->
                <div class="drop_down_profile">
                    <div class="drop_down_image"><img src="../../files/icons/user2.png" alt=""></div>
                    <div class="drop_down_name">Precious Mzembe</div>
                </div>

                <div class="dropdown_option profile_option">
                    <img src="../../files/icons/user.png" alt="">
                    Profile
                </div>
                <div class="dropdown_option logout_option">
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
            $(".dropdown_pane").toggle(500);
        }
    </script>
</body>

</html>