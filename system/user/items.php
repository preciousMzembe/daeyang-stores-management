<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/items.css">
</head>

<body>
    <!-- top -->
    <?php require("./top.php") ?>

    <!-- body -->
    <section class="body_pane">
        <!-- navigation -->
        <?php require("./nav.php") ?>

        <!-- information -->
        <div class="details_pane">
            <!-- items top -->
            <div class="items_top_pane">
                Items In Stock : <span>20</span>
            </div>

            <!-- search and print report -->
            <div class="search_print_pane">
                <!-- search -->
                <div class="search_pane">
                    <input type="text" name="item" id="item" placeholder="search item...">
                    <div class="search_button">
                        <img src="../../files/icons/search.png" alt="">
                    </div>
                </div>

                <!-- print report -->
                <div class="print_report_pane">
                    <div>print report</div>
                    <div class="print_report_options">
                        <!-- download -->
                        <div class="print_report_button">
                            <img src="../../files/icons/download.png" title="download">
                        </div>
                        <!-- mail -->
                        <div class="print_report_button">
                            <img src="../../files/icons/mail.png" title="mail">
                        </div>
                    </div>
                </div>
            </div>

            <!-- items list -->
            <div class="items_list_title">Items List</div>
            <div class="items_list_pane">
n
            </div>
        </div>
    </section>
</body>

</html>