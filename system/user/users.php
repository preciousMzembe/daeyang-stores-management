<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/users.css">
</head>

<body>
    <!-- top -->
    <?php require("./top.php") ?>

    <!-- databse functions for this file --------------------------------------------- -->
    <?php
    // check user position
    if ($database->user_details['position'] == "user") {
        header("location: index.php");
    }

    ?>

    <!-- ---------------------------------------------------------------------------- -->

    <!-- body -->
    <section class="body_pane">
        <!-- navigation -->
        <?php require("./nav.php") ?>

        <!-- information -->
        <div class="details_pane">
            <!-- users top -->
            <div class="users_top_pane">
                <div class="user_title">System Users</div>
                <div class="add_user_button">Add</div>
            </div>

            <!-- users table -->
            <div class="users_table_pane">
                <div class="users_table_headings">
                    <div>Name</div>
                    <div>Position</div>
                    <div>Action</div>
                </div>

                <!-- users list -->
                <?php for ($i = 1; $i <= 5; $i++) { ?>
                    <div class="users_table_row">
                        <div>Name</div>
                        <div>Position</div>
                        <div class="user_row_action">
                            <div><img src="../../files/icons/edit_user.png" alt="" title="edit"></div>
                            <div><img src="../../files/icons/lock_user.png" alt="" title="lock"></div>
                            <div><img src="../../files/icons/delete_user.png" alt="" title="delete"></div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
</body>

</html>