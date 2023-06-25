<?php
ob_start();

class Database
{
    private $conn;
    private $user_id;
    public $user_details;
    public $system_status;

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

        if ($_SESSION['user_id'] == "") {
            header("location: ../../index.php");
        } else {
            $this->user_id = $_SESSION['user_id'];
        }

        // get user details
        $sql = "SELECT * FROM `users` WHERE `id` = '$this->user_id'";
        $results = mysqli_query($this->conn, $sql);
        $user = mysqli_fetch_assoc($results);

        if (empty($user)) {
            header("location: ../../index.php");
        } else {
            $this->user_details = $user;
        }

        // check user status
        if ($user['status'] == "0") {
            $this->logout();
        }

        // check system status
        $sql = "SELECT * FROM `system` WHERE `id` = '1'";
        $results = mysqli_query($this->conn, $sql);
        $system = mysqli_fetch_assoc($results);
        $this->system_status = $system['status'];

        if ($this->system_status == 0 && $user['position'] != "developer") {
            header("location: ../../index.php");
        }
    }

    // get staff
    function get_staff(){
        $sql = "SELECT `fname`, `lname` FROM `users` WHERE `position` = 'staff'";
        $results = mysqli_query($this->conn, $sql);
        $staff = mysqli_fetch_all($results, MYSQLI_ASSOC);
        return $staff;
    }

    // get current stock
    function get_current_stock()
    {
        $sql = "SELECT `balance` FROM `items`";
        $results = mysqli_query($this->conn, $sql);
        $items = mysqli_fetch_all($results, MYSQLI_ASSOC);

        if (empty($items)) {
            return 0;
        } else {
            $current_stock = 0;
            foreach ($items as $item) {
                $current_stock += (int)$item['balance'];
            }
            return $current_stock;
        }
    }

    // get stock value
    function get_stock_value()
    {
        $sql = "SELECT `balance`, `price_per_unit` FROM `items`";
        $results = mysqli_query($this->conn, $sql);
        $items = mysqli_fetch_all($results, MYSQLI_ASSOC);

        if (empty($items)) {
            return 0;
        } else {
            $stock_value = 0;
            foreach ($items as $item) {
                $item_price = (float)$item['price_per_unit'] * (int)$item['balance'];
                $stock_value += (float)$item_price;
            }
            return $stock_value;
        }
    }

    // get total items
    function get_total_items()
    {
        $sql = "SELECT COUNT(*) FROM `items`";
        $results = mysqli_query($this->conn, $sql);
        $total_items = mysqli_fetch_assoc($results);
        return $total_items["COUNT(*)"];
    }

    // get items
    function get_items($name = "all", $names = false)
    {
        if ($names) {
            $sql = "SELECT `name` FROM `items` ORDER BY `name`";
        } else {
            $sql = "SELECT * FROM `items` ORDER BY `name`";
        }

        // get single item
        if ($name != "all") {
            $sql = "SELECT * FROM `items` WHERE `name` LIKE '%$name%' ORDER BY `name`";
        }

        $results = mysqli_query($this->conn, $sql);
        $items = mysqli_fetch_all($results, MYSQLI_ASSOC);
        return $items;
    }

    // get items below reorder levels
    function get_items_below_reorder_levels()
    {
        $sql = "SELECT `name`, `balance`, `reorder_level` FROM `items`";
        $results = mysqli_query($this->conn, $sql);
        $all_items = mysqli_fetch_all($results, MYSQLI_ASSOC);

        $items = [];
        foreach ($all_items as $item) {
            if ($item['balance'] <= $item['reorder_level']) {
                $items[$item['name']]['balance'] = $item['balance'];
                $items[$item['name']]['reorder_level'] = $item['reorder_level'];
            }
        }
        return $items;
    }

    // get item information
    function get_item_information($name)
    {
        $sql = "SELECT * FROM `items` WHERE `name` LIKE '%$name%'";
        $results = mysqli_query($this->conn, $sql);
        $item = mysqli_fetch_assoc($results);
        return $item;
    }

    // change item name
    function change_item_name($data)
    {
        $errors = [];
        $old_name = $this->clean_input($data['item_name']);
        $new_name = $this->clean_input($data['new_item_name']);

        if ($old_name == $new_name) {
            $errors['message'] = "old name and new name are just the same";
            return $errors;
        }

        $sql = "SELECT `name` FROM `items` WHERE `name` = '$new_name'";
        $results = mysqli_query($this->conn, $sql);
        $item = mysqli_fetch_assoc($results);

        if (!empty($item)) {
            $errors['message'] = "there is already an item with the same name";
            $errors['merge'] = true;
            return $errors;
        }

        // update items
        $sql = "UPDATE `items` SET `name`='$new_name' WHERE `name` = '$old_name'";
        if (mysqli_query($this->conn, $sql)) {
            // update stock in
            $sql = "UPDATE `stock_in` SET `item`='$new_name' WHERE `item` = '$old_name'";
            if (mysqli_query($this->conn, $sql)) {
                // update stock out
                $sql = "UPDATE `stock_out` SET `item`='$new_name' WHERE `item` = '$old_name'";
                mysqli_query($this->conn, $sql);
            }
        }
    }

    // merge items
    function merge_items($data)
    {
        $old_item = $this->clean_input($_POST['old_item']);
        $new_item = $this->clean_input($_POST['new_item']);

        // get details of old item
        $sql = "SELECT `balance`, `price_per_unit` FROM `items` WHERE `name` = '$old_item'";
        $results = mysqli_query($this->conn, $sql);
        $old_item_details = mysqli_fetch_assoc($results);

        // get details of new item
        $sql = "SELECT `balance`, `price_per_unit` FROM `items` WHERE `name` = '$new_item'";
        $results = mysqli_query($this->conn, $sql);
        $new_item_details = mysqli_fetch_assoc($results);

        // merge
        $new_balance = (int)$old_item_details['balance'] + (int)$new_item_details['balance'];
        if ((float)$old_item_details['price_per_unit'] > (float)$new_item_details['price_per_unit']) {
            $new_price_per_unit = (float)$old_item_details['price_per_unit'];
        } else {
            $new_price_per_unit = (float)$new_item_details['price_per_unit'];
        }

        // new item details
        $sql = "UPDATE `items` SET `balance`='$new_balance',`price_per_unit`='$new_price_per_unit' WHERE `name` = '$new_item'";
        if (mysqli_query($this->conn, $sql)) {
            // delete old item
            $sql = "DELETE FROM `items` WHERE `name` = '$old_item'";
            if (mysqli_query($this->conn, $sql)) {
                // change old item stock in and out details
                $sql = "UPDATE `stock_in` SET `item`='$new_item' WHERE `item` = '$old_item'";
                if (mysqli_query($this->conn, $sql)) {
                    $sql = "UPDATE `stock_out` SET `item`='$new_item' WHERE `item` = '$old_item'";
                    mysqli_query($this->conn, $sql);
                }

                // calculate in and out
                $sql = "SELECT DISTINCT * 
                    FROM (
                        (SELECT `id`, `item`, `quantity`, `in_balance`, NULL AS `out_balance`, `created_at` FROM `stock_in` WHERE `item` = 'pc' ORDER BY `created_at`)
                        UNION ALL
                        (SELECT `id`, `item`, `quantity`, NULL AS `in_balance`, `out_balance`, `created_at` FROM `stock_out` WHERE `item` = 'pc' ORDER BY `created_at`)
                    ) t ORDER BY `created_at`";
                $results = mysqli_query($this->conn, $sql);
                $in_out_details = mysqli_fetch_all($results, MYSQLI_ASSOC);

                $balance = 0;
                foreach ($in_out_details as $detail) {
                    if ($detail['in_balance'] != null) {
                        // stock in
                        $balance += (int)$detail['quantity'];
                        $sql = "UPDATE `stock_in` SET `in_balance`='$balance' WHERE `id` = '" . $detail['id'] . "'";
                        mysqli_query($this->conn, $sql);
                    } else {
                        // stock out
                        $balance -= (int)$detail['quantity'];
                        $sql = "UPDATE `stock_out` SET `out_balance`='$balance' WHERE `id` = '" . $detail['id'] . "'";
                        mysqli_query($this->conn, $sql);
                    }
                }

                return true;
            }
        }
    }

    // change reorder level
    function change_reorder_level($data)
    {
        $item = $this->clean_input($data['item_name']);
        $level = $this->clean_input($data['reorder_level']);

        $sql = "UPDATE `items` SET `reorder_level`='$level' WHERE `name` = '$item'";
        mysqli_query($this->conn, $sql);
        return true;
    }

    // get item stock in and out
    function get_item_stock_in_and_out($name)
    {
        $sql = "SELECT DISTINCT * 
        FROM (
            (SELECT `id`, `item`, `quantity`, `price_per_unit`, `total_amount`, `supplier`, `deliverd_by`, `checked_by`, `issued_by`, `remarks`, `in_balance`, NULL AS `out_balance`, NULL AS `purpose`, NUll AS `requested_by`, NULL AS `distributed_by`, `created_at` FROM `stock_in` WHERE `item` = '$name' ORDER BY `created_at`)
            UNION ALL
            (SELECT `id`, `item`, `quantity`, NULL AS `price_per_unit`, NULL AS `total_amount`, NULL AS `supplier`, NULL AS `deliverd_by`, `checked_by`, NULL AS `issued_by`, NULL AS `remarks`, NULL AS `in_balance`, `out_balance`, `purpose`, `requested_by`, `distributed_by`, `created_at` FROM `stock_out` WHERE `item` = '$name' ORDER BY `created_at`)
        ) t ORDER BY `created_at` DESC";
        $results = mysqli_query($this->conn, $sql);
        $details = mysqli_fetch_all($results, MYSQLI_ASSOC);
        return $details;
    }

    // get stock in
    function get_stock_in($item = "all", $limit = 0)
    {
        if ($item == "all") {
            if ($limit == 0) {
                $sql = "SELECT * FROM `stock_in` ORDER BY `created_at` DESC";
            } else {
                $sql = "SELECT * FROM `stock_in` ORDER BY `created_at` DESC LIMIT $limit";
            }
        } else {
            if ($limit == 0) {
                $sql = "SELECT * FROM `stock_in` WHERE `item` LIKE '%$item%' ORDER BY `created_at` DESC";
            } else {
                $sql = "SELECT * FROM `stock_in` WHERE `item` LIKE '%$item$' ORDER BY `created_at` DESC LIMIT $limit";
            }
        }

        $results = mysqli_query($this->conn, $sql);
        $stock_in = mysqli_fetch_all($results, MYSQLI_ASSOC);
        return $stock_in;
    }

    // get stock out
    function get_stock_out($item = "all", $limit = 0)
    {
        if ($item == "all") {
            if ($limit == 0) {
                $sql = "SELECT * FROM `stock_out` ORDER BY `created_at` DESC";
            } else {
                $sql = "SELECT * FROM `stock_out` ORDER BY `created_at` DESC LIMIT $limit";
            }
        } else {
            if ($limit == 0) {
                $sql = "SELECT * FROM `stock_out` WHERE `item` Like '%$item%' ORDER BY `created_at` DESC";
            } else {
                $sql = "SELECT * FROM `stock_out` WHERE `item` LIKE '%$item%' ORDER BY `created_at` DESC LIMIT $limit";
            }
        }

        $results = mysqli_query($this->conn, $sql);
        $stock_out = mysqli_fetch_all($results, MYSQLI_ASSOC);
        return $stock_out;
    }

    // stock in process
    function stock_in($data)
    {
        $errors = [];

        $name = $this->clean_input($data['item']);
        $supplier = $this->clean_input($data['supplier']);

        $quantity = $this->clean_input($data['quantity']);
        if ($quantity <= 0) {
            $errors['quantity'] = "quantity can not be less than 1";
            return $errors;
        }

        $price_per_unit = $this->clean_input($data['price_per_unit']);
        $price_per_unit = preg_replace("/[^0-9.]/", "", $price_per_unit);

        $total_amount = (float)$price_per_unit * (int)$quantity;
        // $total_amount = $this->clean_input($data['total_amount']);
        // $total_amount = preg_replace("/[^0-9.]/", "", $total_amount);

        $remarks = $this->clean_input($data['remarks']);
        $deliverd_by = $this->clean_input($data['deliverd_by']);
        $checked_by = $this->clean_input($data['checked_by']);
        $issued_by = $this->clean_input($data['issued_by']);

        // check if item is in database
        $sql = "SELECT `id`, `balance`, `price_per_unit` FROM `items` WHERE `name` = '$name'";
        $results = mysqli_query($this->conn, $sql);
        $item = mysqli_fetch_assoc($results);

        if (empty($item)) {
            // item not in database
            // insert
            $sql = "INSERT INTO `items` (`name`) VALUES ('$name')";
            mysqli_query($this->conn, $sql);

            // get details
            $sql = "SELECT `id`, `balance`, `price_per_unit` FROM `items` WHERE `name` = '$name'";
            $results = mysqli_query($this->conn, $sql);
            $item = mysqli_fetch_assoc($results);
        }

        // values to insert
        $item_id = $item['id'];
        $new_balance = (int)$item['balance'] + (int)$quantity;
        $stock_in_date = date("Y-m-d H:i:s");

        if ($price_per_unit > $item['price_per_unit']) {
            $new_price_per_unit = $price_per_unit;
        } else {
            $new_price_per_unit = $item['price_per_unit'];
        }

        $stock_value = (float)$new_balance * (float)$new_price_per_unit;

        // update item
        $sql = "UPDATE `items` SET `balance`='$new_balance',`price_per_unit`='$new_price_per_unit',`stock_in_date`='$stock_in_date' WHERE `id` = '$item_id'";
        if (mysqli_query($this->conn, $sql)) {
            // stock in
            $sql = "INSERT INTO `stock_in`(`item`, `quantity`, `price_per_unit`, `total_amount`, `supplier`, `deliverd_by`, `checked_by`, `issued_by`, `remarks`, `in_balance`) 
                                    VALUES ('$name','$quantity','$price_per_unit','$total_amount','$supplier','$deliverd_by','$checked_by','$issued_by','$remarks','$new_balance')";

            if (mysqli_query($this->conn, $sql)) {
                header("Refresh:0");
            }
        }
    }

    // stock out process
    function stock_out($data)
    {
        $errors = [];

        $name = $this->clean_input($data['item']);

        $quantity = $this->clean_input($data['quantity']);
        if ($quantity <= 0) {
            $errors['quantity'] = "quantity can not be less than 1";
            return $errors;
        }

        $purpose = $this->clean_input($data['purpose']);
        $requested_by = $this->clean_input($data['requested_by']);
        $checked_by = $this->clean_input($data['checked_by']);
        $distributed_by = $this->clean_input($data['distributed_by']);

        // get quantity from database
        $sql = "SELECT `balance` FROM `items` WHERE `name` = '$name'";
        $results = mysqli_query($this->conn, $sql);
        $balance = mysqli_fetch_assoc($results);

        if ($quantity > $balance['balance']) {
            echo $balance['balance'];
            $errors['quantity'] = "there is only " . $balance['balance'] . " " . $name . " in stock";
            return $errors;
        }

        $new_balance = $balance['balance'] - $quantity;
        $stock_out_date = date("Y-m-d H:i:s");

        // insert stock out
        $sql = "INSERT INTO `stock_out`(`item`, `quantity`, `out_balance`, `purpose`, `requested_by`, `checked_by`, `distributed_by`) 
                            VALUES ('$name','$quantity','$new_balance','$purpose','$requested_by','$checked_by','$distributed_by')";

        if (mysqli_query($this->conn, $sql)) {
            // ipdate item
            $sql = "UPDATE `items` SET `balance`='$new_balance',`stock_out_date`='$stock_out_date' WHERE `name` = '$name'";
            if (mysqli_query($this->conn, $sql)) {
                header("Refresh:0");
            }
        }
    }

    // change email
    function change_email($data)
    {
        $email = $this->clean_input($data['email']);

        $errors = [];

        $sql = "SELECT `email` FROM `users` WHERE `email` = '$email'";
        $results = mysqli_query($this->conn, $sql);
        $user = mysqli_fetch_assoc($results);

        if (!empty($user)) {
            $errors['error'] = "email is already used for another account";
            return $errors;
        }

        $sql = "UPDATE `users` SET `email`='$email' WHERE `id` = '" . $this->user_details['id'] . "'";
        if (mysqli_query($this->conn, $sql)) {
            return $errors;
        }
    }

    // change password
    function change_password($data)
    {
        $password = $this->clean_input($data['password']);

        $errors = [];

        if (strlen($password) < 6) {
            $errors['error'] = "password should not be less than 6 characters";
            return $errors;
        }

        $sql = "UPDATE `users` SET `password`='$password' WHERE `id` = '" . $this->user_details['id'] . "'";
        if (mysqli_query($this->conn, $sql)) {
            return $errors;
        }
    }

    // add user
    function add_user($data)
    {
        $fname = $this->clean_input($data['fname']);
        $lname = $this->clean_input($data['lname']);
        $position = $this->clean_input($data['position']);
        $email = $this->clean_input($data['email']);
        $password = $this->clean_input($data['password']);

        // check email
        $errors = [];
        $sql = "SELECT `email` FROM `users` WHERE `email` = '$email'";
        $results = mysqli_query($this->conn, $sql);
        $email_results = mysqli_fetch_assoc($results);

        if (!empty($email_results)) {
            $errors['email'] = "there is an account with the same email";
            return $errors;
        }

        $sql = "INSERT INTO `users`(`email`, `password`, `fname`, `lname`, `position`) 
                            VALUES ('$email','$password','$fname','$lname','$position')";
        mysqli_query($this->conn, $sql);
    }

    // get users
    function get_users($active = true)
    {
        if ($active) {
            // get all active users
            $sql = "SELECT `id`, `fname`, `lname`, `email`, `password`,  `position`, `status` FROM `users` WHERE `status` = '1' AND `id` != '" . $this->user_details['id'] . "' AND `position` != 'developer'";
            $results = mysqli_query($this->conn, $sql);
            $users = mysqli_fetch_all($results, MYSQLI_ASSOC);
            return $users;
        } else {
            // get all locked users
            $sql = "SELECT `id`, `fname`, `lname`, `position`, `status` FROM `users` WHERE `status` = '0' AND `id` != '" . $this->user_details['id'] . "' AND `position` != 'developer'";
            $results = mysqli_query($this->conn, $sql);
            $users = mysqli_fetch_all($results, MYSQLI_ASSOC);
            return $users;
        }
    }

    // lock user
    function lock_user($id)
    {
        $sql = "UPDATE `users` SET `status`='0' WHERE `id` = '$id'";
        mysqli_query($this->conn, $sql);
        return true;
    }

    // unlock user
    function unlock_user($id)
    {
        $sql = "UPDATE `users` SET `status`='1' WHERE `id` = '$id'";
        mysqli_query($this->conn, $sql);
        return true;
    }

    // delete user
    function delete_user($id)
    {
        $sql = "DELETE FROM `users` WHERE `id` = '$id'";
        mysqli_query($this->conn, $sql);
        return true;
    }

    // get item all reports
    function get_item_stock_in_and_out_reports($data)
    {
        $name = $this->clean_input($_POST['item']);
        $type = $this->clean_input($_POST['type']);
        $start_date = $this->clean_input($_POST['start_date']);
        $end_date = $this->clean_input($_POST['end_date']);

        if (empty($start_date) && empty($end_date)) {
            // empty dates
            $sql = "SELECT DISTINCT * 
            FROM (
                (SELECT `id`, `item`, `quantity`, `price_per_unit`, `total_amount`, `supplier`, `deliverd_by`, `checked_by`, `issued_by`, `remarks`, `in_balance`, NULL AS `out_balance`, NULL AS `purpose`, NUll AS `requested_by`, NULL AS `distributed_by`, `created_at` FROM `stock_in` WHERE `item` = '$name' ORDER BY `created_at`)
                UNION ALL
                (SELECT `id`, `item`, `quantity`, NULL AS `price_per_unit`, NULL AS `total_amount`, NULL AS `supplier`, NULL AS `deliverd_by`, `checked_by`, NULL AS `issued_by`, NULL AS `remarks`, NULL AS `in_balance`, `out_balance`, `purpose`, `requested_by`, `distributed_by`, `created_at` FROM `stock_out` WHERE `item` = '$name' ORDER BY `created_at`)
            ) t ORDER BY `created_at` DESC";
        } elseif (!empty($start_date) && !empty($end_date)) {
            // all dates available
            $start_date = date("Y-m-d", strtotime($this->clean_input($_POST['start_date'])));
            $end_date = date("Y-m-d", strtotime($this->clean_input($_POST['end_date']) . ' + 1 days'));

            $sql = "SELECT DISTINCT * 
            FROM (
                (SELECT `id`, `item`, `quantity`, `price_per_unit`, `total_amount`, `supplier`, `deliverd_by`, `checked_by`, `issued_by`, `remarks`, `in_balance`, NULL AS `out_balance`, NULL AS `purpose`, NUll AS `requested_by`, NULL AS `distributed_by`, `created_at` FROM `stock_in` WHERE `item` = '$name' AND `created_at` >= '$start_date' AND `created_at` <= '$end_date' ORDER BY `created_at`)
                UNION ALL
                (SELECT `id`, `item`, `quantity`, NULL AS `price_per_unit`, NULL AS `total_amount`, NULL AS `supplier`, NULL AS `deliverd_by`, `checked_by`, NULL AS `issued_by`, NULL AS `remarks`, NULL AS `in_balance`, `out_balance`, `purpose`, `requested_by`, `distributed_by`, `created_at` FROM `stock_out` WHERE `item` = '$name' AND `created_at` >= '$start_date' AND `created_at` <= '$end_date' ORDER BY `created_at`)
            ) t ORDER BY `created_at` DESC";
        } elseif (!empty($start_date) && empty($end_date)) {
            // start date
            $start_date = date("Y-m-d", strtotime($this->clean_input($_POST['start_date'])));

            $sql = "SELECT DISTINCT * 
            FROM (
                (SELECT `id`, `item`, `quantity`, `price_per_unit`, `total_amount`, `supplier`, `deliverd_by`, `checked_by`, `issued_by`, `remarks`, `in_balance`, NULL AS `out_balance`, NULL AS `purpose`, NUll AS `requested_by`, NULL AS `distributed_by`, `created_at` FROM `stock_in` WHERE `item` = '$name' AND `created_at` >= '$start_date' ORDER BY `created_at`)
                UNION ALL
                (SELECT `id`, `item`, `quantity`, NULL AS `price_per_unit`, NULL AS `total_amount`, NULL AS `supplier`, NULL AS `deliverd_by`, `checked_by`, NULL AS `issued_by`, NULL AS `remarks`, NULL AS `in_balance`, `out_balance`, `purpose`, `requested_by`, `distributed_by`, `created_at` FROM `stock_out` WHERE `item` = '$name' AND `created_at` >= '$start_date' ORDER BY `created_at`)
            ) t ORDER BY `created_at` DESC";
        } elseif (empty($start_date) && !empty($end_date)) {
            // end date
            $end_date = date("Y-m-d", strtotime($this->clean_input($_POST['end_date']) . ' + 1 days'));

            $sql = "SELECT DISTINCT * 
            FROM (
                (SELECT `id`, `item`, `quantity`, `price_per_unit`, `total_amount`, `supplier`, `deliverd_by`, `checked_by`, `issued_by`, `remarks`, `in_balance`, NULL AS `out_balance`, NULL AS `purpose`, NUll AS `requested_by`, NULL AS `distributed_by`, `created_at` FROM `stock_in` WHERE `item` = '$name' AND `created_at` <= '$end_date' ORDER BY `created_at`)
                UNION ALL
                (SELECT `id`, `item`, `quantity`, NULL AS `price_per_unit`, NULL AS `total_amount`, NULL AS `supplier`, NULL AS `deliverd_by`, `checked_by`, NULL AS `issued_by`, NULL AS `remarks`, NULL AS `in_balance`, `out_balance`, `purpose`, `requested_by`, `distributed_by`, `created_at` FROM `stock_out` WHERE `item` = '$name' AND `created_at` <= '$end_date' ORDER BY `created_at`)
            ) t ORDER BY `created_at` DESC";
        }
        $results = mysqli_query($this->conn, $sql);
        $details = mysqli_fetch_all($results, MYSQLI_ASSOC);
        return $details;
    }

    // get balance reorts
    function get_balance_reports($data)
    {
        $item = $this->clean_input($_POST['item']);
        $type = $this->clean_input($_POST['type']);
        $start_date = $this->clean_input($_POST['start_date']);
        $end_date = $this->clean_input($_POST['end_date']);

        if ($item != "all") {
            $sql = "SELECT `name`, `balance` FROM `items` WHERE `name` = '$item'";
        } else {
            $sql = "SELECT `name`, `balance` FROM `items`";
        }
        $results = mysqli_query($this->conn, $sql);
        $balances = mysqli_fetch_all($results, MYSQLI_ASSOC);
        return $balances;
    }

    // get stock in reports
    function get_stock_in_reports($data)
    {
        $item = $this->clean_input($_POST['item']);
        $type = $this->clean_input($_POST['type']);
        $start_date = $this->clean_input($_POST['start_date']);
        $end_date = $this->clean_input($_POST['end_date']);

        if (empty($start_date) && empty($end_date)) {
            // empty dates
            if ($item != "all") {
                $sql = "SELECT `item`, `quantity`, `price_per_unit`, `checked_by`, `created_at` FROM `stock_in` WHERE `item` = '$item' ORDER BY `created_at` DESC";
            } else {
                $sql = "SELECT `item`, `quantity`, `price_per_unit`, `checked_by`, `created_at` FROM `stock_in` ORDER BY `created_at` DESC";
            }
        } elseif (!empty($start_date) && !empty($end_date)) {
            // all dates available
            $start_date = date("Y-m-d", strtotime($this->clean_input($_POST['start_date'])));
            $end_date = date("Y-m-d", strtotime($this->clean_input($_POST['end_date']) . ' + 1 days'));

            if ($item != "all") {
                $sql = "SELECT `item`, `quantity`, `price_per_unit`, `checked_by`, `created_at` FROM `stock_in` WHERE `item` = '$item' AND `created_at` >= '$start_date' AND `created_at` <= '$end_date' ORDER BY `created_at` DESC";
            } else {
                $sql = "SELECT `item`, `quantity`, `price_per_unit`, `checked_by`, `created_at` FROM `stock_in` WHERE `created_at` >= '$start_date' AND `created_at` <= '$end_date' ORDER BY `created_at` DESC";
            }
        } elseif (!empty($start_date) && empty($end_date)) {
            // start date
            $start_date = date("Y-m-d", strtotime($this->clean_input($_POST['start_date'])));

            if ($item != "all") {
                $sql = "SELECT `item`, `quantity`, `price_per_unit`, `checked_by`, `created_at` FROM `stock_in` WHERE `item` = '$item' AND `created_at` >= '$start_date' ORDER BY `created_at` DESC";
            } else {
                $sql = "SELECT `item`, `quantity`, `price_per_unit`, `checked_by`, `created_at` FROM `stock_in` WHERE `created_at` >= '$start_date' ORDER BY `created_at` DESC";
            }
        } elseif (empty($start_date) && !empty($end_date)) {
            // end date
            $end_date = date("Y-m-d", strtotime($this->clean_input($_POST['end_date']) . ' + 1 days'));

            if ($item != "all") {
                $sql = "SELECT `item`, `quantity`, `price_per_unit`, `checked_by`, `created_at` FROM `stock_in` WHERE `item` = '$item' AND `created_at` <= '$end_date' ORDER BY `created_at` DESC";
            } else {
                $sql = "SELECT `item`, `quantity`, `price_per_unit`, `checked_by`, `created_at` FROM `stock_in` WHERE `created_at` <= '$end_date' ORDER BY `created_at` DESC";
            }
        }

        $results = mysqli_query($this->conn, $sql);
        $stock_ins = mysqli_fetch_all($results, MYSQLI_ASSOC);
        return $stock_ins;
    }

    // get stock out reports
    function get_stock_out_reports($data)
    {
        $item = $this->clean_input($_POST['item']);
        $type = $this->clean_input($_POST['type']);
        $start_date = $this->clean_input($_POST['start_date']);
        $end_date = $this->clean_input($_POST['end_date']);

        if (empty($start_date) && empty($end_date)) {
            // empty dates
            if ($item != "all") {
                $sql = "SELECT `item`, `quantity`, `requested_by`, `distributed_by`, `created_at` FROM `stock_out` WHERE `item` = '$item' ORDER BY `created_at` DESC";
            } else {
                $sql = "SELECT `item`, `quantity`, `requested_by`, `distributed_by`, `created_at` FROM `stock_out` ORDER BY `created_at` DESC";
            }
        } elseif (!empty($start_date) && !empty($end_date)) {
            // all dates available
            $start_date = date("Y-m-d", strtotime($this->clean_input($_POST['start_date'])));
            $end_date = date("Y-m-d", strtotime($this->clean_input($_POST['end_date']) . ' + 1 days'));

            if ($item != "all") {
                $sql = "SELECT `item`, `quantity`, `requested_by`, `distributed_by`, `created_at` FROM `stock_out` WHERE `item` = '$item' AND `created_at` >= '$start_date' AND `created_at` <= '$end_date' ORDER BY `created_at` DESC";
            } else {
                $sql = "SELECT `item`, `quantity`, `requested_by`, `distributed_by`, `created_at` FROM `stock_out` WHERE `created_at` >= '$start_date' AND `created_at` <= '$end_date' ORDER BY `created_at` DESC";
            }
        } elseif (!empty($start_date) && empty($end_date)) {
            // start date
            $start_date = date("Y-m-d", strtotime($this->clean_input($_POST['start_date'])));

            if ($item != "all") {
                $sql = "SELECT `item`, `quantity`, `requested_by`, `distributed_by`, `created_at` FROM `stock_out` WHERE `item` = '$item' AND `created_at` >= '$start_date' ORDER BY `created_at` DESC";
            } else {
                $sql = "SELECT `item`, `quantity`, `requested_by`, `distributed_by`, `created_at` FROM `stock_out` WHERE `created_at` >= '$start_date' ORDER BY `created_at` DESC";
            }
        } elseif (empty($start_date) && !empty($end_date)) {
            // end date
            $end_date = date("Y-m-d", strtotime($this->clean_input($_POST['end_date']) . ' + 1 days'));

            if ($item != "all") {
                $sql = "SELECT `item`, `quantity`, `requested_by`, `distributed_by`, `created_at` FROM `stock_out` WHERE `item` = '$item' AND `created_at` <= '$end_date' ORDER BY `created_at` DESC";
            } else {
                $sql = "SELECT `item`, `quantity`, `requested_by`, `distributed_by`, `created_at` FROM `stock_out` WHERE `created_at` <= '$end_date' ORDER BY `created_at` DESC";
            }
        }

        $results = mysqli_query($this->conn, $sql);
        $stock_ins = mysqli_fetch_all($results, MYSQLI_ASSOC);
        return $stock_ins;
    }

    // get analytics 
    function get_analytics($year)
    {
        $analystics = [];

        // get yearly updates
        $sql = "SELECT `total_amount`, `created_at` FROM `stock_in` WHERE YEAR(`created_at`) = '$year' ";
        $results = mysqli_query($this->conn, $sql);
        $details = mysqli_fetch_all($results, MYSQLI_ASSOC);

        $yearly = [
            "01" => 0,
            "02" => 0,
            "03" => 0,
            "04" => 0,
            "05" => 0,
            "06" => 0,
            "07" => 0,
            "08" => 0,
            "09" => 0,
            "10" => 0,
            "11" => 0,
            "12" => 0,
        ];

        foreach ($details as $detail) {
            $month =  date("m", strtotime($detail['created_at']));
            (float)$yearly[$month] += (float)$detail['total_amount'];
        }

        $analystics['yearly'] = $yearly;

        // get years in database
        $sql = "SELECT DISTINCT YEAR(`created_at`) AS 'year' FROM `stock_in` ORDER BY `created_at` DESC";
        $results = mysqli_query($this->conn, $sql);
        $years = mysqli_fetch_all($results, MYSQLI_ASSOC);

        $analystics['years'] = $years;

        // get years comparisone
        $comparisone_years =  [];
        foreach ($years as $year) {
            $comparisone_years[$year['year']] = 0;
        }

        $sql = "SELECT `total_amount`, YEAR(`created_at`) AS 'year' FROM `stock_in`";
        $results = mysqli_query($this->conn, $sql);
        $years_comparisones = mysqli_fetch_all($results, MYSQLI_ASSOC);
        foreach ($years_comparisones as $i) {
            (float)$comparisone_years[$i['year']] += (float)$i['total_amount'];
        }

        $analystics['comparisone_years'] = $comparisone_years;


        return $analystics;
    }

    // logout
    function logout()
    {
        $_SESSION['user_id'] = "";
        header("Refresh:0");
    }

    // lock system
    function lock_system($lock = true)
    {
        if ($lock) {
            $sql = "UPDATE `system` SET `status`='0' WHERE `id` = '1'";
            mysqli_query($this->conn, $sql);
            return true;
        } else {
            $sql = "UPDATE `system` SET `status`='1' WHERE `id` = '1'";
            mysqli_query($this->conn, $sql);
            return true;
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
