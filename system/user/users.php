<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/index.css">
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

    ?>

    <!-- ---------------------------------------------------------------------------- -->

    <!-- body -->
    <section class="body_pane">
        <!-- navigation -->
        <?php require("./nav.php") ?>

        <!-- information -->
        <div class="details_pane">
            
        </div>
    </section>
</body>

</html>