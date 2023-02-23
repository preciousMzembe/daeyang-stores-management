<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/nav.css">
</head>

<body>
    <div class="nav_pane">
        <?php $getWholeUrl = "http://" . $_SERVER['HTTP_HOST'] . "" . $_SERVER['REQUEST_URI'] . ""; ?>

        <div class="nav_option <?php if (strpos($getWholeUrl, "index") == true) {
                                    echo 'active';
                                } ?>" onclick="window.location='index.php'">Dashboard</div>

        <div class="nav_option <?php if (strpos($getWholeUrl, "items") == true) {
                                    echo 'active';
                                } ?>" onclick="window.location='items.php'">Items</div>

        <div class="nav_option <?php if (strpos($getWholeUrl, "stockin") == true) {
                                    echo 'active';
                                } ?>" onclick="window.location='stockin.php'">Stock In</div>

        <div class="nav_option <?php if (strpos($getWholeUrl, "stockout") == true) {
                                    echo 'active';
                                } ?>" onclick="window.location='stockout.php'">Stock Out</div>

        <div class="nav_option <?php if (strpos($getWholeUrl, "analytics") == true) {
                                    echo 'active';
                                } ?>" onclick="window.location='analytics.php'">Analytics</div>
    </div>
</body>

</html>