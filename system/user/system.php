<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/index.css">
    <link rel="stylesheet" href="./css/system.css">
</head>

<body>
    <!-- top -->
    <?php require("./top.php") ?>

    <!-- databse functions for this file --------------------------------------------- -->
    <?php
    // check user position
    if ($database->user_details['position'] != "developer") {
        header("location: index.php");
    }

    // get users
    $users = $database->get_users();

    // lock
    if(isset($_POST['lock'])){
        $lock = $database->lock_system();
        header('location: system.php');
    }

    // unlock
    if(isset($_POST['unlock'])){
        $lock = $database->lock_system(false);
        header('location: system.php');
    }

    ?>

    <!-- ---------------------------------------------------------------------------- -->

    <!-- body -->
    <section class="body_pane">
        <!-- navigation -->
        <?php require("./nav.php") ?>

        <!-- information -->
        <div class="details_pane">
            <!-- lock -->
            <div class="system_lock_pane">
                <div class="system_settings_title">System Settings</div>
                <div class="lock_system">
                    <?php if ($database->system_status == 1) { ?>
                        <div>the system is open to be used!</div>
                        <div class="lock_system_button" onclick="lock()">Lock</div>
                    <?php } else { ?>
                        <div>the system is closed to all users!</div>
                        <div class="lock_system_button" onclick="unlock()">Unlock</div>
                    <?php } ?>
                </div>
            </div>

            <!-- users -->
            <div class="system_users">System Users</div>
            <div class="system_users_table">
                <div class="system_users_table_titles">
                    <div>Name</div>
                    <div>Email</div>
                    <div>Password</div>
                    <div>Position</div>
                </div>

                <?php foreach ($users as $user) { ?>
                    <div class="system_users_table_user">
                        <div class="name"><?php echo $user['fname'] . " " . $user['lname'] ?></div>
                        <div class="name"><?php echo $user['email'] ?></div>
                        <div><?php echo $user['password'] ?></div>
                        <div class="name"><?php echo $user['position'] ?></div>
                    </div>
                <?php } ?>

            </div>
        </div>
    </section>

    <script>
        // lock
        function lock(){
            let url = window.location.href;

            const form = document.createElement('form');
            form.method = "post";
            form.action = url;

            const hiddenField = document.createElement('input');
            hiddenField.type = 'hidden';
            hiddenField.name = "lock";
            hiddenField.value = "lock";

            form.appendChild(hiddenField);

            document.body.appendChild(form);
            form.submit();
        }

        // unlock
        function unlock(){
            let url = window.location.href;

            const form = document.createElement('form');
            form.method = "post";
            form.action = url;

            const hiddenField = document.createElement('input');
            hiddenField.type = 'hidden';
            hiddenField.name = "unlock";
            hiddenField.value = "unlock";

            form.appendChild(hiddenField);

            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>

</html>