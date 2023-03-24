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

    <!-- databse functions for this file --------------------------------------------- -->
    <?php

    // get items
    if (isset($_POST['search_item'])) {
        $search_item = trim($_POST['search_item']);
        if ($search_item != "") {
            $items = $database->get_items($name = "$search_item");
        } else {
            $items = $database->get_items();
        }
    } else {
        $items = $database->get_items();
    }

    // get single item details
    if (isset($_POST['item'])) {
        $item_name = $_POST['item'];
        $item_details = $database->get_item_information($item_name);
        $item_stock_in_and_out = $database->get_item_stock_in_and_out($item_name);
    }

    // edit item
    if (isset($_POST['edit_item'])) {
        $item_name = $_POST['edit_item'];
        $edit_item_details = $database->get_item_information($item_name);
    }

    if (isset($_POST['new_item_name'])) {
        $edit_errors = $database->change_item_name($_POST);
        if (empty($edit_errors)) {
            header('location: items.php');
        } else {
            $edit_item_details = $database->get_item_information($_POST['item_name']);
        }
    }

    if (isset($_POST['reorder_level'])) {
        $change = $database->change_reorder_level($_POST);
        if ($change) {
            header('location: items.php');
        }
    }

    if (isset($_POST['merge_items'])) {
        $merge = $database->merge_items($_POST);
        if ($merge) {
            header('location: items.php');
        }
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
                Items In Stock : <span><?php echo count($items) ?></span>
            </div>

            <!-- search and print report -->
            <div class="search_print_pane">
                <!-- search -->
                <form action="items.php" method="POST" class="search_pane">
                    <input type="text" name="search_item" id="item" placeholder="search item..." value="<?php echo $_POST['search_item'] ?? "" ?>">
                    <button type="submit" class="search_button">
                        <img src="../../files/icons/search.png" alt="">
                    </button type="submit">
                </form>

                <!-- print report -->
                <!-- <div class="print_report_pane">
                    <div>print report</div>
                    <div class="print_report_options">
                        <div class="print_report_button">
                            <img src="../../files/icons/download.png" title="download">
                        </div>
                        <div class="print_report_button">
                            <img src="../../files/icons/mail.png" title="mail">
                        </div>
                    </div>
                </div> -->
            </div>

            <!-- items list -->
            <div class="items_list_title">Items List</div>
            <div class="items_list_pane">
                <!-- check if there are items in the database -->
                <?php if (!empty($items)) { ?>
                    <!-- list headings -->
                    <div class="items_headings">
                        <div>Item</div>
                        <div>Quantity</div>
                        <div>Reorder Level</div>
                        <div>Last Stock In</div>
                        <!-- <div>Last Stock Out</div> -->
                        <div></div>
                    </div>

                    <!-- list item -->
                    <?php foreach ($items as $item) { ?>
                        <div class="items_item">
                            <div><?php echo $item['name'] ?></div>
                            <div><?php echo number_format((float)$item['balance']) ?></div>
                            <div><?php echo number_format((float)$item['reorder_level']) ?></div>
                            <div><?php echo date("d M Y", strtotime($item['stock_in_date'])) ?></div>
                            <!-- <div><?php echo date("d M Y", strtotime($item['stock_out_date']) ?? "") ?></div> -->
                            <div class="view_item_buttons">
                                <div class="view_item_button" onclick="get_item('<?php echo $item['name'] ?>')">view</div>
                                <div class="view_item_button edit_item_button" onclick="edit_item('<?php echo $item['name'] ?>')">edit</div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <div class="not_found_pane">
                        <div class="not_found_text">no items to show</div>
                        <div class="not_found_image">
                            <img src="../../files/icons/not_found.png" alt="">
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>

    <!-- single item details -->
    <section class="item_details_pane">
        <?php if (!empty($item_details)) { ?>
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
                        <div class="item_details_name"><?php echo $item_details['name'] ?></div>

                        <!-- print report -->
                        <!-- <div class="print_report_pane">
                            <div class="print_report_title">print detaild report</div>
                            <div class="print_report_form">
                                <div class="print_report_input">
                                    <div class="start_date">
                                        <input type="text" name="start_date" id="start_date" placeholder="start date">
                                    </div>
                                    <div class="end_date">
                                        <input type="text" name="end_date" id="end_date" placeholder="end date">
                                    </div>
                                </div>

                                <div class="print_report_button">
                                    <img src="../../files/icons/download.png" title="download">
                                </div>
                                <div class="print_report_button">
                                    <img src="../../files/icons/mail.png" title="mail">
                                </div>

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
                        </div> -->
                    </div>

                    <!-- item current stock and value -->
                    <div class="item_current_Stock_and_value">
                        <!-- current stock -->
                        <div class="item_current_stock_pane">
                            <div class="item_current_stock_head">Current Stock</div>
                            <div class="item_current_stock_number"><?php echo $item_details['balance'] ?> <span>units</span></div>
                        </div>

                        <!-- stock value -->
                        <div class="item_stock_value">
                            <div class="item_current_stock_head">Price/Unit</div>
                            <div class="item_current_stock_number"><span>MK</span> <?php echo number_format((int)$item_details['price_per_unit']) ?> </div>
                        </div>

                        <!-- stock value -->
                        <div class="item_stock_value">
                            <div class="item_current_stock_head">Stock Value</div>
                            <div class="item_current_stock_number"><span>MK</span> <?php echo number_format((int)$item_details['balance'] * (float)$item_details['price_per_unit']) ?> </div>
                        </div>

                        <!-- reorder level -->
                        <div class="item_current_stock_pane">
                            <div class="item_current_stock_head">Reorder Level</div>
                            <div class="item_current_stock_number"><?php echo $item_details['reorder_level'] ?> <span>units</span></div>
                        </div>
                    </div>

                    <!-- stock inn and out details -->
                    <div class="stock_in_out_details_title">stock in and out details</div>

                    <div class="item_stock_in_and_out_headings">
                        <div>Date</div>
                        <div>In</div>
                        <div>Out</div>
                        <div>Balance</div>
                        <div></div>
                    </div>

                </div>

                <!-- details table moving part-->
                <div class="item_stock_in_and_out_table">

                    <div class="item_stock_details_rows">
                        <?php
                        $i = 1;
                        foreach ($item_stock_in_and_out as $stock) {
                        ?>
                            <!-- in or out information -->
                            <div class="item_in_or_out">
                                <!-- item top row -->
                                <div class="item_in_out_top">
                                    <div class=""><?php echo date("d M Y", strtotime($stock['created_at'])) ?></div>
                                    <div class=""><?php if ($stock['in_balance'] != null) {
                                                        echo number_format($stock['quantity']);
                                                    } ?></div>
                                    <div class=""><?php if ($stock['out_balance'] != null) {
                                                        echo number_format($stock['quantity']);
                                                    } ?></div>
                                    <div class=""><?php if ($stock['in_balance'] != null) {
                                                        echo number_format($stock['in_balance']);
                                                    } else {
                                                        echo number_format($stock['out_balance']);
                                                    } ?></div>
                                    <div class="item_drop">
                                        <div class="item_drop_button item_drop_button_<?php echo $stock['id'] . $i ?>" onclick="show_hide_item_info(<?php echo $stock['id'] . $i ?>)">
                                            <img src="../../files/icons/down2.png" alt="">
                                        </div>
                                    </div>
                                </div>

                                <!-- item more details -->
                                <div class="item_in_or_out_bottom item_in_or_out_bottom_<?php echo $stock['id'] . $i;
                                                                                        $i++; ?>">
                                    <?php if ($stock['in_balance'] != null) { ?>
                                        <!-- stock in information -->
                                        <div class="">
                                            <div class="item_more_details_title">Price / Unit</div>
                                            <div class="item_more_details_detail"><span>MK</span> <?php echo number_format((float)$stock['price_per_unit']) ?></div>
                                        </div>

                                        <div class="">
                                            <div class="item_more_details_title">Total Amount</div>
                                            <div class="item_more_details_detail"><span>MK</span> <?php echo number_format((float)$stock['total_amount']) ?></div>
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
                                    <?php } else { ?>
                                        <!-- stock out information -->
                                        <div class="">
                                            <div class="item_more_details_title">Purpose</div>
                                            <div class="item_more_details_detail"><?php echo $stock['purpose'] ?></div>
                                        </div>

                                        <div class="">
                                            <div class="item_more_details_title">Requested By</div>
                                            <div class="item_more_details_detail"><?php echo $stock['requested_by'] ?></div>
                                        </div>

                                        <div class="">
                                            <div class="item_more_details_title">Checked By</div>
                                            <div class="item_more_details_detail"><?php echo $stock['checked_by'] ?></div>
                                        </div>

                                        <div class="">
                                            <div class="item_more_details_title">Distributed By</div>
                                            <div class="item_more_details_detail"><?php echo $stock['distributed_by'] ?></div>
                                        </div>
                                    <?php } ?>

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
        <?php } ?>
    </section>

    <!-- edit item pane -->
    <section class="edit_item_pane">
        <?php if (!empty($edit_item_details)) { ?>
            <div class="edit_item_pane_in">
                <!-- close -->
                <div class="edit_item_close">
                    <div class="edit_item_name"><?php echo $edit_item_details['name'] ?></div>
                    <div class="edit_item_close_button" onclick="show_edit_item()"><img src="../../files/icons/close.png" alt=""></div>
                </div>

                <div class="edit_item_title">Edit Details</div>

                <?php if (($database->user_details['position'] != "admin")) { ?>
                    <form action="items.php" method="POST" class="edit_item_name_form">
                        <input type="hidden" name="item_name" value="<?php echo $edit_item_details['name'] ?>">
                        <div><label for="">new name</label></div>
                        <div><input type="text" name="new_item_name" value="<?php echo $_POST['new_item_name'] ?? "" ?>" required></div>
                        <div class="edit_error">
                            <div class="error_pane"><?php echo $edit_errors['message'] ?? "" ?></div>
                        </div>
                        <?php if (!empty($edit_errors['merge'])) { ?>
                            <div class="merge_pane">
                                <p>merge items as <span><?php echo $_POST['new_item_name'] ?? "" ?></span></p>
                                <div class="merge_button" onclick="merge_items('<?php echo $_POST['item_name'] ?>', '<?php echo $_POST['new_item_name'] ?>')">merge</div>
                            </div>
                        <?php } ?>
                        <div><button type="submit">Change</button></div>
                    </form>
                <?php } ?>

                <form action="items.php" method="POST" class="edit_item_reorder_form">
                    <input type="hidden" name="item_name" value="<?php echo $edit_item_details['name'] ?>">
                    <div><label for="">reorder levels</label></div>
                    <div><input type="number" name="reorder_level" id="" min="0" value="<?php echo $edit_item_details['reorder_level'] ?>"></div>
                    <div><button type="submit">Change</button></div>
                </form>

            </div>
        <?php } ?>
    </section>

    <script>
        // hide and how item
        <?php if (!empty($item_details)) { ?>
            $(".item_details_pane").css({
                "visibility": "visible"
            });
        <?php } ?>

        function show_hide_item() {
            $(".item_details_pane").css({
                "visibility": "hidden"
            });
        }

        // hide and show stock more details
        $(".item_in_or_out_bottom").hide();

        function show_hide_item_info(id) {
            let button_class_name = "item_drop_button_" + id
            let info_class_name = "item_in_or_out_bottom_" + id
            $('.' + button_class_name).toggleClass("button_up");
            $('.' + info_class_name).toggle();
        }

        // get an item
        function get_item(item) {
            let url = window.location.href;

            const form = document.createElement('form');
            form.method = "post";
            form.action = url;

            const hiddenField = document.createElement('input');
            hiddenField.type = 'hidden';
            hiddenField.name = "item";
            hiddenField.value = item;

            form.appendChild(hiddenField);

            document.body.appendChild(form);
            form.submit();
        }

        // show and hide edit item
        <?php if (!empty($edit_item_details) || !empty($edit_errors)) { ?>
            $(".edit_item_pane").css({
                "visibility": "visible"
            });
        <?php } ?>

        function show_edit_item() {
            $(".edit_item_pane").css({
                "visibility": "hidden"
            });
        }

        function edit_item(item) {
            let url = window.location.href;

            const form = document.createElement('form');
            form.method = "post";
            form.action = url;

            const hiddenField = document.createElement('input');
            hiddenField.type = 'hidden';
            hiddenField.name = "edit_item";
            hiddenField.value = item;

            form.appendChild(hiddenField);

            document.body.appendChild(form);
            form.submit();
        }

        function merge_items(old_item, new_item) {
            let url = window.location.href;

            const form = document.createElement('form');
            form.method = "post";
            form.action = url;

            const old_item_input = document.createElement('input');
            old_item_input.type = 'hidden';
            old_item_input.name = "old_item";
            old_item_input.value = old_item;

            const new_item_input = document.createElement('input');
            new_item_input.type = 'hidden';
            new_item_input.name = "new_item";
            new_item_input.value = new_item;

            const merge_items_input = document.createElement('input');
            merge_items_input.type = 'hidden';
            merge_items_input.name = "merge_items";
            merge_items_input.value = "merge";

            form.appendChild(old_item_input);
            form.appendChild(new_item_input);
            form.appendChild(merge_items_input);

            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>

</html>