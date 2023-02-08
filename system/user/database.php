<?php

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

        // check user position
        $sql = "SELECT * FROM `users` WHERE `id` = '$this->user_id'";
        $results = mysqli_query($this->conn, $sql);
        $user = mysqli_fetch_assoc($results);

        if (empty($user)) {
            header("location: ../../index.php");
        } else {
            $this->user_details = $user;
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
        $sql = "SELECT `stock_value` FROM `items`";
        $results = mysqli_query($this->conn, $sql);
        $items = mysqli_fetch_all($results, MYSQLI_ASSOC);

        if (empty($items)) {
            return 0;
        } else {
            $stock_value = 0;
            foreach ($items as $item) {
                $stock_value += (int)$item['stock_value'];
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
    function get_items($names = false)
    {
        if ($names) {
            $sql = "SELECT `name` FROM `items`";
        } else {
            $sql = "SELECT * FROM `items`";
        }

        $results = mysqli_query($this->conn, $sql);
        $items = mysqli_fetch_all($results, MYSQLI_ASSOC);
        return $items;
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
                $sql = "SELECT * FROM `stock_in` WHERE `item_id` = '$item' ORDER BY `created_at` DESC";
            } else {
                $sql = "SELECT * FROM `stock_in` WHERE `item_id` = '$item' ORDER BY `created_at` DESC LIMIT $limit";
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
                $sql = "SELECT * FROM `stock_out` WHERE `item_id` = '$item' ORDER BY `created_at` DESC";
            } else {
                $sql = "SELECT * FROM `stock_out` WHERE `item_id` = '$item' ORDER BY `created_at` DESC LIMIT $limit";
            }
        }

        $results = mysqli_query($this->conn, $sql);
        $stock_out = mysqli_fetch_all($results, MYSQLI_ASSOC);
        return $stock_out;
    }

    // stock in process
    function stock_in($data)
    {
        $name = $this->clean_input($data['item']);
        $supplier = $this->clean_input($data['supplier']);
        $quantity = $this->clean_input($data['quantity']);

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
        $sql = "UPDATE `items` SET `balance`='$new_balance',`price_per_unit`='$new_price_per_unit',`stock_in_date`='$stock_in_date',`stock_value`='$stock_value' WHERE `id` = '$item_id'";
        if (mysqli_query($this->conn, $sql)) {
            // stock in
            $sql = "INSERT INTO `stock_in`(`item`, `quantity`, `price_per_unit`, `total_amount`, `supplier`, `deliverd_by`, `checked_by`, `issued_by`, `remarks`, `in_balance`) 
                                    VALUES ('$name','$quantity','$price_per_unit','$total_amount','$supplier','$deliverd_by','$checked_by','$issued_by','$remarks','$new_balance')";

            if(mysqli_query($this->conn, $sql)){
                header("Refresh:0");
            }
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
