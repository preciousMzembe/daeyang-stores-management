<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/items.css">
    <link rel="stylesheet" href="./css/index.css">
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
                <!-- list headings -->
                <div class="items_headings">
                    <div>Item</div>
                    <div>Quantity</div>
                    <div>Last Stock In</div>
                    <div>Last Stock Out</div>
                    <div></div>
                </div>

                <!-- list item -->
                <?php for ($i = 1; $i <= 5; $i++) { ?>
                    <div class="items_item">
                        <div>note book</div>
                        <div>30</div>
                        <div>03/02/2023</div>
                        <div>03/01/2023</div>
                        <div>
                            <div class="view_item_button" onclick="show_hide_item()">view more</div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>

    <!-- single item details -->
    <section class="item_details_pane">
        <div class="item_details_pane_in">
            <div class="item_details_not_moving">
                <!-- close button -->
                <div class="close_pane">
                    <div class="close_button" onclick="show_hide_item()">
                        <img src="../../files/icons/close.png" alt="">
                    </div>
                </div>

                <!-- item name and print report -->
                <div class="item_details_print_report">
                    <!-- item name -->
                    <div class="item_details_name">item name</div>

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

                <!-- item current stock and value -->
                <div class="item_current_Stock_and_value">
                    <!-- current stock -->
                    <div class="item_current_stock_pane">
                        <div class="item_current_stock_head">Current Stock</div>
                        <div class="item_current_stock_number">20 <span>units</span></div>
                    </div>

                    <!-- stock value -->
                    <div class="item_stock_value">
                        <div class="item_current_stock_head">Stock Value</div>
                        <div class="item_current_stock_number"><span>MK</span> 100,000</div>
                    </div>
                </div>

                <!-- stock inn and out details -->
                <div class="stock_in_out_details_title">stock in and out details</div>

            </div>

            <!-- details table moving part-->
            <div class="item_stock_in_and_out_table">
                <!-- headings -->
                <div class="item_stock_in_and_out_headings">
                    <div>Date</div>
                    <div>In</div>
                    <div>Out</div>
                    <div>Balance</div>
                    <div></div>
                </div>

                <div class="item_stock_details_rows">
                    <?php for ($i = 1; $i <= 3; $i++) { ?>
                        <!-- in or out information -->
                        <div class="item_in_or_out">
                            <!-- item top row -->
                            <div class="item_in_out_top">
                                <div class="">02/01/2023</div>
                                <div class="">10</div>
                                <div class=""></div>
                                <div class="">20</div>
                                <div class="item_drop">
                                    <div class="item_drop_button item_drop_button_<?php echo $i ?>" onclick="show_hide_item_info(<?php echo $i ?>)">
                                        <img src="../../files/icons/down2.png" alt="">
                                    </div>
                                </div>
                            </div>

                            <!-- item more details -->
                            <div class="item_in_or_out_bottom item_in_or_out_bottom_<?php echo $i ?>">
                                <div class="">
                                    <div class="item_more_details_title">Price / Unit</div>
                                    <div class="item_more_details_detail"><span>MK</span> 1,000</div>
                                </div>

                                <div class="">
                                    <div class="item_more_details_title">Total Amount</div>
                                    <div class="item_more_details_detail"><span>MK</span> 20,000</div>
                                </div>

                                <div class="">
                                    <div class="item_more_details_title">Suplier</div>
                                    <div class="item_more_details_detail">eagle</div>
                                </div>

                                <div class="">
                                    <div class="item_more_details_title">Deliverd By</div>
                                    <div class="item_more_details_detail">name</div>
                                </div>

                                <div class="">
                                    <div class="item_more_details_title">Checked By</div>
                                    <div class="item_more_details_detail">name</div>
                                </div>

                                <div class="">
                                    <div class="item_more_details_title">Issued By</div>
                                    <div class="item_more_details_detail">name</div>
                                </div>

                                <div class="">
                                    <div class="item_more_details_title">Remarks</div>
                                    <div class="item_more_details_detail">Lorem ipsum dolor sit</div>
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
                </div>
            </div>

        </div>
    </section>

    <script>
        // hide and how item
        $(".item_details_pane").hide();

        function show_hide_item(){
            $(".item_details_pane").toggle();
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