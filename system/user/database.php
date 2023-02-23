<?php
ob_start();

class Database
{
    private $conn;
    private $user_id;
    public $user_details;

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

        // get user position
        $sql = "SELECT * FROM `users` WHERE `id` = '$this->user_id'";
        $results = mysqli_query($this->conn, $sql);
        $user = mysqli_fetch_assoc($results);

        if (empty($user)) {
            header("location: ../../index.php");
        } else {
            $this->user_details = $user;
        }

        // check system status
        $sql = "SELECT * FROM `system` WHERE `id` = '1'";
        $results = mysqli_query($this->conn, $sql);
        $system = mysqli_fetch_assoc($results);

        if ($system['status'] == 0 && $user['position'] != "developer") {
            header("location: ../../index.php");
        }

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
            $sql = "SELECT `name` FROM `items`";
        } else {
            $sql = "SELECT * FROM `items`";
        }

        // get single item
        if ($name != "all") {
            $sql = "SELECT * FROM `items` WHERE `name` LIKE '%$name%'";
        }

        $results = mysqli_query($this->conn, $sql);
        $items = mysqli_fetch_all($results, MYSQLI_ASSOC);
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

        $total_amount = $this->clean_input($data['total_amount']);
        $total_amount = preg_replace("/[^0-9.]/", "", $total_amount);

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

    // change password
    function change_password($data)
    {
        $old_password = $this->clean_input($data['old_password']);
        $new_password = $this->clean_input($data['new_password']);
        $confirm_password = $this->clean_input($data['confirm_password']);

        $errors = [];

        if ($this->user_details['password'] != $old_password) {
            $errors['old_password'] = "wrong old password entered";
            return $errors;
        }

        if ($new_password != $confirm_password) {
            $errors['confirm_password'] = "wrong confirmation password entered";
            return $errors;
        }

        $sql = "UPDATE `users` SET `password`='$new_password' WHERE `id` = '" . $this->user_details['id'] . "'";
        if (mysqli_query($this->conn, $sql)) {
            return $errors;
        }
    }

    // logout
    function logout()
    {
        $_SESSION['user_id'] = "";
        header("Refresh:0");
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
