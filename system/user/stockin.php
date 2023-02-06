<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/stockin.css">
    <link rel="stylesheet" href="./css/index.css">
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
                Stock In Details
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
                    <div class="print_report_title">print detaild report</div>
                    <div class="print_report_form">
                        <div class="print_report_input">
                            <!-- start date -->
                            <div class="start_date">
                                <input type="text" name="start_date" id="start_date" placeholder="start date">
                            </div>
                            <!-- end date -->
                            <div class="end_date">
                                <input type="text" name="end_date" id="end_date" placeholder="end date">
                            </div>
                        </div>

                        <!-- download -->
                        <div class="print_report_button">
                            <img src="../../files/icons/download.png" title="download">
                        </div>
                        <!-- mail -->
                        <div class="print_report_button">
                            <img src="../../files/icons/mail.png" title="mail">
                        </div>

                        <!-- input script -->
                        <script>
                            $(document).ready(function() {
                                $("#start_date").datepicker({
                                    showOn: "button",
                                    buttonImage: "../../files/icons/date.png",
                                    buttonImageOnly: true,
                                    buttonText: "Select date"
                                });

                                $("#end_date").datepicker({
                                    showOn: "button",
                                    buttonImage: "../../files/icons/date.png",
                                    buttonImageOnly: true,
                                    buttonText: "Select date"
                                });
                            })
                        </script>
                    </div>
                </div>
            </div>

            <!-- items list -->
            <div class="items_list_title">
                <div class="">Stock In Updates</div>
                <div class="add_stock_button">Stock In</div>
            </div>
            <div class="items_list_pane">
                <!-- list headings -->
                <div class="items_headings">
                    <div>Item</div>
                    <div>Quantity</div>
                    <div>Price / Unit</div>
                    <div>Date</div>
                    <div></div>
                </div>

                <!-- list item -->
                <?php for ($i = 1; $i <= 5; $i++) { ?>
                    <div class="items_item">
                        <div>note book</div>
                        <div>30</div>
                        <div>MK 3,000</div>
                        <div>03/01/2023</div>
                        <div>
                            <div class="view_item_button">details</div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
</body>
</html>