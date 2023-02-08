<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/stockout.css">
    <link rel="stylesheet" href="./css/stockin.css">
    <link rel="stylesheet" href="./css/index.css">
    <link rel="stylesheet" href="./css/items.css">
</head>

<body>
    <!-- top -->
    <?php require("./top.php") ?>

    <!-- databse functions for this file --------------------------------------------- -->
    <?php

    // get stock out
    $stock_out = $database->get_stock_out();

    ?>

    <!-- ---------------------------------------------------------------------------- -->

    <!-- body -->
    <section class="body_pane">
        <!-- navigation -->
        <?php require("./nav.php") ?>

        <!-- information -->
        <div class="details_pane">
            <!-- items top -->
            <div class="items_top_pane">
                Stock Out Details
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
                <div class="">Stock Out Updates</div>
                <div class="add_stock_button" onclick="show_hide_item()">Stock Out</div>
            </div>
            <div class="items_list_pane">
                <!-- check if there any stock out to show -->
                <?php if (!empty($stock_out)) { ?>
                    <!-- list headings -->
                    <div class="items_headings">
                        <div>Item</div>
                        <div>Quantity</div>
                        <div>Balance</div>
                        <div>Date</div>
                        <div></div>
                    </div>

                    <!-- list item -->
                    <?php for ($i = 1; $i <= 5; $i++) { ?>
                        <!-- in or out information -->
                        <div class="item_in_or_out">
                            <!-- item top row -->
                            <div class="item_in_out_top">
                                <div class="">note book</div>
                                <div class="">10</div>
                                <div class="">15</div>
                                <div class="">03/03/2023</div>
                                <div class="item_drop">
                                    <div class="item_drop_button item_drop_button_<?php echo $i ?>" onclick="show_hide_item_info(<?php echo $i ?>)">
                                        <img src="../../files/icons/down2.png" alt="">
                                    </div>
                                </div>
                            </div>

                            <!-- item more details -->
                            <div class="item_in_or_out_bottom item_in_or_out_bottom_<?php echo $i ?>">

                                <div class="">
                                    <div class="item_more_details_title">Purpose</div>
                                    <div class="item_more_details_detail">purpose</div>
                                </div>

                                <div class="">
                                    <div class="item_more_details_title">Requested By</div>
                                    <div class="item_more_details_detail">name</div>
                                </div>

                                <div class="">
                                    <div class="item_more_details_title">Checked By</div>
                                    <div class="item_more_details_detail">name</div>
                                </div>

                                <div class="">
                                    <div class="item_more_details_title">Distributed By</div>
                                    <div class="item_more_details_detail">name</div>
                                </div>

                                <div class="">
                                    <!-- <div class="item_more_details_title">print</div> -->
                                    <div class="item_more_details_detail">
                                        <div class="item_in_out_print_button"><img src="../../files/icons/download.png" alt=""></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <div class="not_found_pane">
                        <div class="not_found_text">no stock out to show</div>
                        <div class="not_found_image">
                            <img src="../../files/icons/not_found.png" alt="">
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>

    <!-- Stock out form -->
    <section class="item_details_pane stock_in_process_pane">
        <div class="item_details_pane_in">
            <!-- close button -->
            <div class="close_pane">
                <div class="close_button" onclick="show_hide_item()">
                    <img src="../../files/icons/close.png" alt="">
                </div>
            </div>

            <!-- stock in form details -->
            <div class="stock_in_form_title">Stock In Form</div>

            <!-- form details -->
            <form action="" method="post">
                <div class="stock_in_inputs">
                    <div class="">
                        <div class="input_label">Item / Stock Name</div>
                        <div><input type="text" name="" id=""></div>
                    </div>

                    <div class="">
                        <div class="input_label">Quantity</div>
                        <div><input type="text" name="" id=""></div>
                    </div>

                    <div class="">
                        <div class="input_label">Purpose</div>
                        <div><textarea name="" id="" cols="30" rows="10"></textarea></div>
                    </div>

                    <div class="">
                        <div class="input_label">Requested By</div>
                        <div><input type="text" name="" id=""></div>
                    </div>

                    <div class="">
                        <div class="input_label">Checked By</div>
                        <div><input type="text" name="" id=""></div>
                    </div>

                    <div class="">
                        <div class="input_label">Distributed By</div>
                        <div><input type="text" name="" id=""></div>
                    </div>
                </div>

                <!-- save button -->
                <div class="stock_in_inputs stock_in_save_pane">
                    <div></div>
                    <div class="stock_in_save_button"><button type="submit">Save</button></div>
                    <div></div>
                </div>
            </form>

        </div>
    </section>

    <script>
        // hide and how item
        $(".stock_in_process_pane").hide();

        function show_hide_item() {
            $(".stock_in_process_pane").toggle();
        }

        // hide and how item more details
        $(".item_in_or_out_bottom").hide();

        function show_hide_item_info(id) {
            let button_class_name = "item_drop_button_" + id
            let info_class_name = "item_in_or_out_bottom_" + id
            $('.' + button_class_name).toggleClass("button_up");
            $('.' + info_class_name).toggle();
        }
    </script>
</body>

</html>