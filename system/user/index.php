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

    // get current stock
    $current_stock = $database->get_current_stock();

    // get stock value
    $stock_value = $database->get_stock_value();

    // get total items
    $total_items = $database->get_total_items();

    // get recent stock in
    $recent_stock_in = $database->get_stock_in("all", 5);

    // get recent stock out
    $recent_stock_out = $database->get_stock_out("all", 5);

    ?>

    <!-- ---------------------------------------------------------------------------- -->

    <!-- body -->
    <section class="body_pane">
        <!-- navigation -->
        <?php require("./nav.php") ?>

        <!-- information -->
        <div class="details_pane">
            <!-- welcome user and print report -->
            <div class="welcome_pane">
                <!-- welcome user -->
                <div class="welcome_pane_user">Welcome <span>Precious Mzembe</span></div>

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

            <!-- check overview -->
            <div class="check_overview_title">stock overview</div>
            <!-- stock overview -->
            <div class="stock_overview_pane">
                <div class="stock_overview_block">
                    <div class="stock_icon">
                        <img src="../../files/icons/stock.png" alt="">
                    </div>
                    <div class="stock_details">
                        <div class="stock_name">Current Stock</div>
                        <div class="stock_number"><?php echo number_format($current_stock) ?></div>
                    </div>
                </div>
                <div class="stock_overview_block">
                    <div class="stock_icon">
                        <img src="../../files/icons/stock.png" alt="">
                    </div>
                    <div class="stock_details">
                        <div class="stock_name">Stock Value</div>
                        <div class="stock_number"><span>MK</span> <?php echo number_format($stock_value) ?></div>
                    </div>
                </div>
                <div class="stock_overview_block">
                    <div class="stock_icon">
                        <img src="../../files/icons/stock.png" alt="">
                    </div>
                    <div class="stock_details">
                        <div class="stock_name">Total Items</div>
                        <div class="stock_number"><?php echo number_format($total_items) ?></div>
                    </div>
                </div>
            </div>

            <!-- items overview -->
            <div class="items_overview_pane">
                <!-- stock in overview -->
                <div class="stock_in_overview">
                    <div class="stock_in_out_title">recent stock in</div>

                    <!-- stock in table -->
                    <div class="stock_in_out_table stock_in_table">
                        <!-- check if there are recent stock in -->
                        <?php if (!empty($recent_stock_in)) { ?>
                            <!-- headings -->
                            <div class="stock_in_headings">
                                <div class="">Item</div>
                                <div class="">Quantity</div>
                                <div class="">Price/Unit</div>
                                <div class="">Date</div>
                            </div>

                            <!-- stock in list -->
                            <?php foreach ($recent_stock_in as $stock_in) { ?>
                                <div class="stock_in_item">
                                    <div class=""><?php echo $stock_in['item'] ?></div>
                                    <div class=""><?php echo number_format($stock_in['quantity']) ?></div>
                                    <div class="">MK <?php echo number_format($stock_in['price_per_unit']) ?></div>
                                    <div class=""><?php echo date("d M Y", strtotime($stock_in['created_at'])) ?></div>
                                </div>
                            <?php } ?>
                        <?php } else { ?>
                            <div class="not_found_pane">
                                <div class="not_found_text">no recent stock in</div>
                                <div class="not_found_image">
                                    <img src="../../files/icons/not_found.png" alt="">
                                </div>
                            </div>
                        <?php } ?>

                    </div>
                </div>

                <!-- stock out overview -->
                <div class="stock_out_overview">
                    <div class="stock_in_out_title">recent stock out</div>

                    <!-- stock out table -->
                    <div class="stock_in_out_table stovk_out_table">
                        <!-- check if there recent stok out to show -->
                        <?php if (!empty($recent_stock_out)) { ?>
                            <!-- headings -->
                            <div class="stock_out_headings">
                                <div class="">Item</div>
                                <div class="">Quantity</div>
                                <div class="">Date</div>
                            </div>

                            <!-- stock out list -->
                            <?php for ($i = 1; $i <= 8; $i++) { ?>
                                <div class="stock_out_item">
                                    <div class="">cable</div>
                                    <div class="">20</div>
                                    <div class="">02/02/2023</div>
                                </div>
                            <?php } ?>
                        <?php } else { ?>
                            <div class="not_found_pane">
                                <div class="not_found_text">no recent stock out</div>
                                <div class="not_found_image">
                                    <img src="../../files/icons/not_found.png" alt="">
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>