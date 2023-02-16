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
    // change password
    if(isset($_POST['change_password'])){
        $errors = $database->change_password($_POST);
        if(empty($errors)){
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
            <div class="chenge_profile_pane">
                <div class="change_password_title">Change Password</div>

                <!-- change passweordform -->
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
            </div>
        </div>
    </section>
</body>
</html>