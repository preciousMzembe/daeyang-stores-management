<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/users.css">
    <link rel="stylesheet" href="./css/items.css">
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

    // add new user
    if (isset($_POST['add_user'])) {
        $add_errors = $database->add_user($_POST);

        if (empty($add_errors)) {
            header("location: users.php");
        }
    }

    // lock user
    if (isset($_POST['lock'])) {
        $lock = $database->lock_user($_POST['lock']);
        if ($lock) {
            header("location: users.php");
        }
    }

    // unlock user
    if (isset($_POST['unlock'])) {
        $unlock = $database->unlock_user($_POST['unlock']);
        if ($unlock) {
            header("location: users.php");
        }
    }

    // delete user
    if (isset($_POST['delete'])) {
        $delete = $database->delete_user($_POST['delete']);
        if ($delete) {
            header("location: users.php");
        }
    }

    // get active and locked users
    $active_users = $database->get_users();
    $locked_users = $database->get_users($active = false);

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
                <div class="add_user_button" onclick="show_hide_item()">Add</div>
            </div>

            <!-- users table -->
            <div class="users_table_pane">
                <!-- users list -->
                <?php if (!empty($active_users)) { ?>
                    <div class="users_table_headings">
                        <div>Name</div>
                        <div>Position</div>
                        <div>Action</div>
                    </div>
                    <?php foreach ($active_users as $user) { ?>
                        <div class="users_table_row">
                            <div id="user_<?php echo $user['id'] ?>"><?php echo $user['fname'] . " " . $user['lname'] ?></div>
                            <div><?php echo $user['position'] ?></div>
                            <div class="user_row_action">
                                <!-- <div><img src="../../files/icons/edit_user.png" alt="" title="edit"></div> -->
                                <div onclick="show_confirmation('<?php echo $user['id'] ?>', 'are you sure you want to lock user?', 'lock')"><img src="../../files/icons/lock_user.png" alt="" title="lock"></div>
                                <div onclick="show_confirmation('<?php echo $user['id'] ?>', 'are you sure you want to delete user?', 'delete')"><img src="../../files/icons/delete_user.png" alt="" title="delete"></div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <div class="not_found_pane">
                        <div class="not_found_text">no users found</div>
                        <div class="not_found_image">
                            <img src="../../files/icons/not_found.png" alt="">
                        </div>
                    </div>
                <?php } ?>
            </div>

            <!-- locked users table -->
            <div class="locked_users_title">Locked Users</div>
            <div class="users_table_pane">
                <!-- users list -->
                <?php if (!empty($locked_users)) { ?>
                    <div class="users_table_headings">
                        <div>Name</div>
                        <div>Position</div>
                        <div>Action</div>
                    </div>
                    <?php foreach ($locked_users as $user) { ?>
                        <div class="users_table_row">
                            <div id="user_<?php echo $user['id'] ?>"><?php echo $user['fname'] . " " . $user['lname'] ?></div>
                            <div><?php echo $user['position'] ?></div>
                            <div class="user_row_action">
                                <div onclick="show_confirmation('<?php echo $user['id'] ?>', 'are you sure you want to unlock user?', 'unlock')"><img src="../../files/icons/lock_user.png" alt="" title="unlock"></div>
                                <div onclick="show_confirmation('<?php echo $user['id'] ?>', 'are you sure you want to delete user?', 'delete')"><img src="../../files/icons/delete_user.png" alt="" title="delete"></div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <div class="not_found_pane">
                        <div class="not_found_text">no locked users found</div>
                        <div class="not_found_image">
                            <img src="../../files/icons/not_found.png" alt="">
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>

    <!-- add user -->
    <section class="item_details_pane">
        <div class="item_details_pane_in add_user_pop_pane">
            <!-- close button -->
            <div class="close_pane">
                <div class="close_button" onclick="show_hide_item()">
                    <img src="../../files/icons/close.png" alt="">
                </div>
            </div>

            <!-- form -->
            <form action="users.php" method="Post">
                <div class="user_form_details">
                    <div>
                        <div class="label">First Name</div>
                        <div><input type="text" name="fname" id="" value="<?php echo $_POST['fname'] ?? "" ?>" required></div>
                    </div>

                    <div>
                        <div class="label">Last Name</div>
                        <div><input type="text" name="lname" id="" value="<?php echo $_POST['lname'] ?? "" ?>" required></div>
                    </div>

                    <div>
                        <div class="label">Position</div>
                        <div>
                            <select name="position" id="" required>
                                <option <?php if (!empty($_POST)) {
                                            if ($_POST['position'] == "staff") {
                                                echo "selected";
                                            }
                                        } ?> value="staff">Staff</option>
                                <option <?php if (!empty($_POST)) {
                                            if ($_POST['position'] == "user") {
                                                echo "selected";
                                            }
                                        } ?> value="user">Manager</option>
                                <option <?php if (!empty($_POST)) {
                                            if ($_POST['position'] == "admin") {
                                                echo "selected";
                                            }
                                        } ?> value="admin">Admin</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <div class="label">Email</div>
                        <div><input type="email" name="email" id="" value="<?php echo $_POST['email'] ?? "" ?>" required></div>
                        <div class="error_pane"><?php echo $add_errors['email'] ?? "" ?></div>
                    </div>

                    <div>
                        <div class="label">Password</div>
                        <div><input type="text" name="password" id="" value="<?php echo $_POST['password'] ?? "" ?>" required></div>
                    </div>
                </div>

                <div class="user_in_save_pane">
                    <div></div>
                    <div><button type="submit" name="add_user">Save</button></div>
                    <div></div>
                </div>
            </form>

        </div>
    </section>

    <!-- confirmation popup -->
    <section class="edit_user_pane">
        <div class="edit_user_in_pane">
            <!-- close -->
            <div class="edit_user_close">
                <div onclick="close_confirmation()"><img src="../../files/icons/close.png" alt=""></div>
            </div>
            <div class="edit_user_message">message</div>
            <div class="edit_user_name">username</div>
            <div class="edit_user_buttons">
                <div class="edit_user_ok_button">Ok</div>
                <div class="edit_user_close_button" onclick="close_confirmation()">Close</div>
            </div>
        </div>
    </section>

    <script>
        // hide and how item
        // $(".item_details_pane").hide();

        <?php if (!empty($add_errors)) { ?>
            $(".item_details_pane").css({
                "visibility": "visible"
            });
        <?php } ?>

        function show_hide_item() {
            let n = $(".item_details_pane").css("visibility");

            if (n == 'hidden') {
                $(".item_details_pane").css({
                    "visibility": "visible"
                });
            } else {
                $(".item_details_pane").css({
                    "visibility": "hidden"
                });
            }
        }

        // edit user buttons
        function show_confirmation(id, message, action) {
            $(".edit_user_message").text(message)
            $(".edit_user_name").text($("#user_" + id).text())

            $(".edit_user_pane").css({
                "visibility": "visible"
            });

            if (action == "lock") {
                $(".edit_user_ok_button").attr("onclick", `lock_user('${id}')`)
            }
            if (action == "delete") {
                $(".edit_user_ok_button").attr("onclick", `delete_user('${id}')`)
            }
            if (action == "unlock") {
                $(".edit_user_ok_button").attr("onclick", `unlock_user('${id}')`)
            }
        }

        function close_confirmation() {
            $(".edit_user_pane").css({
                "visibility": "hidden"
            });
        }

        function lock_user(id) {
            let url = window.location.href;

            const form = document.createElement('form');
            form.method = "post";
            form.action = url;

            const hiddenField = document.createElement('input');
            hiddenField.type = 'hidden';
            hiddenField.name = "lock";
            hiddenField.value = id;

            form.appendChild(hiddenField);

            document.body.appendChild(form);
            form.submit();
        }

        function delete_user(id) {
            let url = window.location.href;

            const form = document.createElement('form');
            form.method = "post";
            form.action = url;

            const hiddenField = document.createElement('input');
            hiddenField.type = 'hidden';
            hiddenField.name = "delete";
            hiddenField.value = id;

            form.appendChild(hiddenField);

            document.body.appendChild(form);
            form.submit();
        }

        function unlock_user(id) {
            let url = window.location.href;

            const form = document.createElement('form');
            form.method = "post";
            form.action = url;

            const hiddenField = document.createElement('input');
            hiddenField.type = 'hidden';
            hiddenField.name = "unlock";
            hiddenField.value = id;

            form.appendChild(hiddenField);

            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>

</html>