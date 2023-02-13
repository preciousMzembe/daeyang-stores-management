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

    <!-- databse functions for this file --------------------------------------------- -->
    <?php

    // get stock in
    if (isset($_POST['search_item'])) {
        $search_item = trim($_POST['search_item']);
        if ($search_item != "") {
            $stock_in = $database->get_stock_in($item = $search_item);
        } else {
            $stock_in = $database->get_stock_in();
        }
    } else {
        $stock_in = $database->get_stock_in();
    }

    // get items list
    $items = $database->get_items(true);

    // stock in process
    if (isset($_POST['stock_in'])) {
        $errors = $database->stock_in($_POST);
    }

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
                Stock In Details
            </div>

            <!-- search and print report -->
            <div class="search_print_pane">
                <!-- search -->
                <form action="stockin.php" method="POST" class="search_pane">
                    <input type="text" name="search_item" id="item" placeholder="search item..." value="<?php echo $_POST['search_item'] ?? "" ?>">
                    <button type="submit" class="search_button">
                        <img src="../../files/icons/search.png" alt="">
                    </button type="submit">
                </form>

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
                <?php if ($database->user_details['position'] == "user") { ?>
                    <div class="add_stock_button" onclick="show_hide_item()">Stock In</div>
                <?php } ?>
            </div>
            <div class="items_list_pane">
                <!-- check if there are any stock in -->
                <?php if (!empty($stock_in)) { ?>
                    <!-- list headings -->
                    <div class="items_headings">
                        <div>Item</div>
                        <div>Quantity</div>
                        <div>Balance</div>
                        <div>Date</div>
                        <div></div>
                    </div>

                    <!-- list item -->
                    <?php foreach ($stock_in as $stock) { ?>
                        <!-- in or out information -->
                        <div class="item_in_or_out">
                            <!-- item top row -->
                            <div class="item_in_out_top">
                                <div class=""><?php echo $stock['item'] ?></div>
                                <div class=""><?php echo number_format($stock['quantity']) ?></div>
                                <div class=""><?php echo number_format($stock['in_balance']) ?></div>
                                <div class=""><?php echo date("d M Y", strtotime($stock['created_at']) ?? "") ?></div>
                                <div class="item_drop">
                                    <div class="item_drop_button item_drop_button_<?php echo $stock['id'] ?>" onclick="show_hide_item_info(<?php echo $stock['id'] ?>)">
                                        <img src="../../files/icons/down2.png" alt="">
                                    </div>
                                </div>
                            </div>

                            <!-- item more details -->
                            <div class="item_in_or_out_bottom item_in_or_out_bottom_<?php echo $stock['id'] ?>">
                                <div class="">
                                    <div class="item_more_details_title">Price per Unit</div>
                                    <div class="item_more_details_detail">MK <?php echo number_format($stock['price_per_unit']) ?></div>
                                </div>

                                <div class="">
                                    <div class="item_more_details_title">Total Amount</div>
                                    <div class="item_more_details_detail"><span>MK</span> <?php echo number_format($stock['total_amount']) ?></div>
                                </div>

                                <div class="">
                                    <div class="item_more_details_title">Supplier</div>
                                    <div class="item_more_details_detail"><?php echo $stock['supplier'] ?></div>
                                </div>

                                <div class="">
                                    <div class="item_more_details_title">Deliverd By</div>
                                    <div class="item_more_details_detail"><?php echo $stock['deliverd_by'] ?></div>
                                </div>

                                <div class="">
                                    <div class="item_more_details_title">Checked By</div>
                                    <div class="item_more_details_detail"><?php echo $stock['checked_by'] ?></div>
                                </div>

                                <div class="">
                                    <div class="item_more_details_title">Issued By</div>
                                    <div class="item_more_details_detail"><?php echo $stock['issued_by'] ?></div>
                                </div>

                                <div class="">
                                    <div class="item_more_details_title">Remarks</div>
                                    <div class="item_more_details_detail"><?php echo $stock['remarks'] ?></div>
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
                        <div class="not_found_text">no stock in to show</div>
                        <div class="not_found_image">
                            <img src="../../files/icons/not_found.png" alt="">
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>

    <!-- Stock in form -->
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
            <form action="stockin.php" method="post">
                <div class="stock_in_inputs">
                    <div class="">
                        <div class="input_label">Item / Stock Name</div>
                        <div>
                            <!-- item name selection or new ite entry -->
                            <select name="item" onchange="if($(this).val()=='customOption'){$(this).hide().prop('disabled',true);$('input[name=item]').show().prop('disabled', false).focus();$(this).val(null);}" required>
                                <option></option>
                                <option value="customOption">[new item]</option>
                                <?php foreach ($items as $item) { ?>
                                    <option <?php if (!empty($_POST['item']) && $_POST['item'] == $item['name']) {
                                                echo "selected";
                                            } ?> value="<?php echo $item['name'] ?>"><?php echo $item['name'] ?></option>
                                <?php } ?>
                            </select>
                            <input name="item" style="display:none;" disabled="disabled" onblur="if($(this).val()==''){$(this).hide().prop('disabled',true);$('select[name=item]').show().prop('disabled', false).focus();}" required>
                        </div>
                    </div>

                    <div class="">
                        <div class="input_label">Supplier</div>
                        <div><input type="text" name="supplier" id="" value="<?php echo $_POST['supplier'] ?? "" ?>"></div>
                    </div>

                    <div class="">
                        <div class="input_label">Quantity</div>
                        <div><input type="number" min="1" name="quantity" id="" value="<?php echo $_POST['quantity'] ?? "" ?>" required></div>
                        <div class="error_pane"><?php echo $errors['quantity'] ?? ""; ?></div>
                    </div>

                    <div class="">
                        <div class="input_label">Price per Unit</div>
                        <div><input type="text" data-type="currency" name="price_per_unit" id="currency-field" value="<?php echo $_POST['price_per_unit'] ?? "" ?>"></div>
                    </div>

                    <div class="">
                        <div class="input_label">Total Amount</div>
                        <div><input type="text" data-type="currency" name="total_amount" id="" value="<?php echo $_POST['total_amount'] ?? "" ?>"></div>
                    </div>

                    <div class="">
                        <div class="input_label">Remarks</div>
                        <div><textarea name="remarks" id=""><?php echo $_POST['remarks'] ?? "" ?></textarea></div>
                    </div>

                    <div class="">
                        <div class="input_label">Deliverd By</div>
                        <div><input type="text" name="deliverd_by" id="" value="<?php echo $_POST['deliverd_by'] ?? "" ?>"></div>
                    </div>

                    <div class="">
                        <div class="input_label">Checked By</div>
                        <div><input type="text" name="checked_by" id="" value="<?php echo $_POST['checked_by'] ?? "" ?>"></div>
                    </div>

                    <div class="">
                        <div class="input_label">Issued By</div>
                        <div><input type="text" name="issued_by" id="" value="<?php echo $_POST['issued_by'] ?? "" ?>"></div>
                    </div>
                </div>

                <!-- save button -->
                <div class="stock_in_inputs stock_in_save_pane">
                    <div></div>
                    <div class="stock_in_save_button"><button type="submit" name="stock_in">Save</button></div>
                    <div></div>
                </div>
            </form>

        </div>
    </section>

    <script src="../../files/js/currency.js"></script>
    <script>
        // hide and how item
        $(".stock_in_process_pane").hide();
        <?php if (!empty($errors)) { ?>
            $(".stock_in_process_pane").show();
        <?php } ?>

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