<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/profile.css">
</head>

<body>
    <!-- top -->
    <?php require("./top.php") ?>

    <!-- database functions for this file --------------------------------------------- -->

    <?php
    // change email
    if (isset($_POST['email'])) {
        $email_error = $database->change_email($_POST);
        if (empty($email_error)) {
            header("location: profile.php");
        }
    }

    // change password
    if (isset($_POST['password'])) {
        $password_error = $database->change_password($_POST);
        if (empty($password_error)) {
            header("location: profile.php");
        }
    }
    ?>

    <!-- ------------------------------------------------------------------------------ -->

    <!-- body -->
    <section class="body_pane">
        <!-- navigation -->
        <?php require("./nav.php") ?>

        <!-- information -->
        <div class="details_pane">
            <div class="profile_title">Profile Details</div>

            <div class="profile_details_pane">
                <div>
                    <div class="profile_detail_title">Email</div>
                    <div class="profile_detail_value"><?php echo $database->user_details['email'] ?></div>
                </div>

                <div>
                    <div class="profile_detail_title">Password</div>
                    <div class="profile_detail_value profile_detail_password">
                        <input class="password_input_display" type="password" value="<?php echo $database->user_details['password'] ?>" disabled>
                        <div class="change_password_view" onclick="show_hide_password()"><img class="password_input_image" src="../../files/icons/show.png" alt=""></div>
                    </div>
                </div>
            </div>

            <div class="change_profile_pane">
                <div class="profile_title">Change Details</div>

                <div class="profile_details_pane">
                    <form action="profile.php" method="POST">
                        <div class="profile_detail_title">Email</div>
                        <div class="profile_detail_input"><input type="email" name="email" value="<?php echo $_POST['email'] ?? "" ?>" placeholder="enter new email address" required></div>
                        <div class="error_pane"><?php echo $email_error['error'] ?? "" ?></div>
                        <div><button type="submit">Change</button></div>
                    </form>

                    <form action="profile.php" method="POST">
                        <div class="profile_detail_title">Password</div>
                        <div class="profile_detail_input"><input type="password" name="password" placeholder="enter new password" required></div>
                        <div class="error_pane"><?php echo $password_error['error'] ?? "" ?></div>
                        <div><button type="submit">Change</button></div>
                    </form>
                </div>
            </div>



            <!-- <div class="chenge_profile_pane">
                <div class="change_password_title">Change Password</div>

                <form action="profile.php" method="POST" class="change_password_form">
                    <div class="change_input">
                        <div><label for="">Old Password</label></div>
                        <div><input type="password" name="old_password" id="" value="<?php echo $_POST['old_password'] ?? "" ?>" required></div>
                        <div class="error_pane"><?php echo $errors['old_password'] ?? "" ?></div>
                    </div>

                    <div class="change_input">
                        <div><label for="">New Password</label></div>
                        <div><input type="password" name="new_password" id="" value="<?php echo $_POST['new_password'] ?? "" ?>" required></div>
                        <div class="error_pane"><?php echo $errors['new_password'] ?? "" ?></div>
                    </div>

                    <div class="change_input">
                        <div><label for="">Confirm Password</label></div>
                        <div><input type="password" name="confirm_password" id="" value="<?php echo $_POST['confirm_password'] ?? "" ?>" required></div>
                        <div class="error_pane"><?php echo $errors['confirm_password'] ?? "" ?></div>
                    </div>

                    <div>
                        <button type="submit" name="change_password">Change</button>
                    </div>
                </form>
            </div> -->
        </div>
    </section>

    <script>
        let i = true
        function show_hide_password(){            
            if(i){
                $(".password_input_display").get(0).type = "text"
                $(".password_input_image").attr("src", "../../files/icons/hide.png")
            }else{
                $(".password_input_display").get(0).type = "password"
                $(".password_input_image").attr("src", "../../files/icons/show.png")
            }            
            i = !i
        }
    </script>
</body>

</html>