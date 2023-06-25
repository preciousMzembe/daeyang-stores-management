<?php

// database file
require("./database.php");
$database = new Database();

// login
if (isset($_POST['login'])) {
    $errors = $database->login($_POST);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DSMS</title>
    <link rel="stylesheet" href="index.css">
    <link rel="icon" type="image/png" href="files/icons/logo.png" />
</head>

<body>
    <!-- 
    Daeyang Stores Management System.
    Auther:    Precious Mzembe.
    ID Number: BScICT/19/054.
 -->

    <section class="wrapper">
        <div class="wrapper_in">
            <!-- image -->
            <div class="image_pane">
                <img src="./files/icons/login_image.png" alt="">
            </div>

            <!-- login information -->
            <form class="login" action="index.php" method="POST">
                <!-- icon and name-->
                <div class="icon_name">
                    <div class="icon_pane">
                        <img src="./files/icons/logo.png" alt="">
                    </div>
                    <div class="name_pane">DYUNI Inventory Management</div>
                </div>

                <!-- login details -->
                <div class="login_headings">
                    <div class="title_pane">Login</div>
                    <div class="subtitle_pane">login to access your account</div>
                </div>

                <div class="form_details">
                    <!-- username -->
                    <div class="form_field">
                        <div class="form_label">Email</div>
                        <div class="form_input">
                            <input type="email" name="email" value="<?php echo $_POST['email'] ?? ""; ?>" required>
                        </div>
                        <div class="input_error"><?php echo $errors['email'] ?? ""; ?></div>
                    </div>

                    <!-- password -->
                    <div class="form_field">
                        <div class="form_label password_label">
                            <div>Password</div>
                            <div class="forgot_password">Forgot password?</div>
                        </div>
                        <div class="form_input">
                            <input type="password" name="password" value="<?php echo $_POST['password'] ?? ""; ?>" required>
                        </div>
                        <div class="input_error"><?php echo $errors['password'] ?? ""; ?></div>
                    </div>

                    <!-- login button -->
                    <div class="form_field">
                        <input class="login_button" type="submit" name="login" value="Login">
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!-- error section -->
    <section class="error_popup">
        <div class="error_popup_in">
            <div class="error_cancel">
                <div onclick="close_popup()"><img src="./files/icons/close.png" alt=""></div>
            </div>
            <div class="error_message"><?php echo $errors ?? ""; ?></div>
            <div class="error_close">
                <div onclick="close_popup()">close</div>
            </div>
        </div>
    </section>

    <script src="./files/js/jquery-3.6.3.min.js"></script>
    <script>
        // check if user is blocked or system is locked
        <?php if (!empty($errors)) { ?>
            $(".error_popup").css("visibility", "visible")
        <?php } ?>

        // close popup
        function close_popup() {
            $(".error_popup").hide()
        }
    </script>
</body>

</html>