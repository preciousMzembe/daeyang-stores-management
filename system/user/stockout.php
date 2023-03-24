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

    <!-- database functions for this file --------------------------------------------- -->
    <?php

    // get stock out
    if (isset($_POST['search_item'])) {
        $search_item = trim($_POST['search_item']);
        if ($search_item != "") {
            $stock_out = $database->get_stock_out($item = $search_item);
        } else {
            $stock_out = $database->get_stock_out();
        }
    } else {
        $stock_out = $database->get_stock_out();
    }

    // get items list
    $items = $database->get_items(true);

    // stock in process
    if (isset($_POST['stock_out'])) {
        $errors = $database->stock_out($_POST);
        if (empty($errors)) {
            header("location: stockout.php");
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
                Stock Out Details
            </div>

            <!-- search and print report -->
            <div class="search_print_pane">
                <!-- search -->
                <form action="stockout.php" method="POST" class="search_pane">
                    <input type="text" name="search_item" id="item" placeholder="search item..." value="<?php echo $_POST['search_item'] ?? "" ?>">
                    <button type="submit" class="search_button">
                        <img src="../../files/icons/search.png" alt="">
                    </button type="submit">
                </form>
            </div>

            <!-- items list -->
            <div class="items_list_title">
                <div class="">Stock Out Updates</div>
                <?php if ($database->user_details['position'] != "admin") { ?>
                    <div class="add_stock_button" onclick="show_hide_item()">Stock Out</div>
                <?php } ?>
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
                    <?php foreach ($stock_out as $stock) { ?>
                        <!-- in or out information -->
                        <div class="item_in_or_out">
                            <!-- item top row -->
                            <div class="item_in_out_top">
                                <div class=""><?php echo $stock['item'] ?></div>
                                <div class=""><?php echo number_format($stock['quantity']) ?></div>
                                <div class=""><?php echo number_format($stock['out_balance']) ?></div>
                                <div class=""><?php echo date("d M Y", strtotime($stock['created_at'])) ?></div>
                                <div class="item_drop">
                                    <div class="item_drop_button item_drop_button_<?php echo $stock['id'] ?>" onclick="show_hide_item_info(<?php echo $stock['id'] ?>)">
                                        <img src="../../files/icons/down2.png" alt="">
                                    </div>
                                </div>
                            </div>

                            <!-- item more details -->
                            <div class="item_in_or_out_bottom item_in_or_out_bottom_<?php echo $stock['id'] ?>">

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
            <div class="stock_in_form_title">Stock Out Form</div>

            <!-- form details -->
            <form action="stockout.php" method="POST">
                <div class="stock_in_inputs">
                    <div class="">
                        <div class="input_label">Item / Stock Name</div>
                        <div>
                            <select name="item" id="" required>
                                <option value=""></option>
                                <?php foreach ($items as $item) { ?>
                                    <option <?php if (!empty($_POST['item']) && $_POST['item'] == $item['name']) {
                                                echo "selected";
                                            } ?> value="<?php echo $item['name'] ?>"><?php echo $item['name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="">
                        <div class="input_label">Quantity</div>
                        <div><input type="number" min="1" name="quantity" id="" required value="<?php echo $_POST['quantity'] ?? "" ?>"></div>
                        <div class="error_pane"><?php echo $errors['quantity'] ?? "" ?></div>
                    </div>

                    <div class="">
                        <div class="input_label">Purpose</div>
                        <div><textarea name="purpose" id="" cols="30" rows="10">
                            <?php echo $_POST['purpose'] ?? "" ?>
                        </textarea></div>
                    </div>

                    <div class="">
                        <div class="input_label">Requested By</div>
                        <div><input type="text" name="requested_by" id="" required value="<?php echo $_POST['requested_by'] ?? "" ?>"></div>
                    </div>

                    <div class="">
                        <div class="input_label">Checked By</div>
                        <div><input type="text" name="checked_by" id="" value="<?php echo $_POST['checked_by'] ?? "" ?>"></div>
                    </div>

                    <div class="">
                        <div class="input_label">Distributed By</div>
                        <div><input type="text" name="distributed_by" id="" required value="<?php echo $_POST['distributed_by'] ?? "" ?>"></div>
                    </div>
                </div>

                <!-- save button -->
                <div class="stock_in_inputs stock_in_save_pane">
                    <div></div>
                    <div class="stock_in_save_button"><button type="submit" name="stock_out">Save</button></div>
                    <div></div>
                </div>
            </form>

        </div>
    </section>

    <script>
        // hide and how item
        // $(".stock_in_process_pane").hide();
        <?php if (!empty($errors)) { ?>
            $(".item_details_pane").css({
                "visibility": "visible"
            });
        <?php } ?>

        function show_hide_item() {
            let n = $(".item_details_pane").css("visibility");

            if (n == 'hidden') {
                $(".item_details_pane").css({
                    "visibility": "visible"
                });
            } else {
                $(".item_details_pane").css({
                    "visibility": "hidden"
                });
            }
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