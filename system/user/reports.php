<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/items.css">
    <link rel="stylesheet" href="./css/index.css">
    <link rel="stylesheet" href="./css/reports.css">
</head>

<body>
    <!-- top -->
    <?php require("./top.php") ?>

    <!-- databse functions for this file --------------------------------------------- -->
    <?php

    // get item names
    $item_names = $database->get_items($name = "all", $names = true);

    // get reports
    if (isset($_POST['get_report'])) {
        if ($_POST['type'] == "all") {
            if ($_POST['item'] != "all") {
                $item_all = $database->get_item_stock_in_and_out_reports($_POST);
            } else {
                echo "all";
            }
        } elseif ($_POST['type'] == "balance") {
            $balances = $database->get_balance_reports($_POST);
            $_SESSION['report_array'] = $balances;
            $_SESSION['item'] = $_POST['item'];
            $_SESSION['report_type'] = "balances";
        } elseif ($_POST['type'] == "stock_in") {
            $stock_ins = $database->get_stock_in_reports($_POST);
            $_SESSION['report_array'] = $stock_ins;
            $_SESSION['report_type'] = "stock_in";
            $_SESSION['start_date'] = $_POST['start_date'];
            $_SESSION['end_date'] = $_POST['end_date'];
        } elseif ($_POST['type'] == "stock_out") {
            $stock_outs = $database->get_stock_out_reports($_POST);
            $_SESSION['report_array'] = $stock_outs;
            $_SESSION['report_type'] = "stock_out";
            $_SESSION['start_date'] = $_POST['start_date'];
            $_SESSION['end_date'] = $_POST['end_date'];
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
                Reports
            </div>

            <!-- selections -->
            <form action="reports.php" method="POST" class="reports_selection_pane">
                <div>
                    <div>Item</div>
                    <div>
                        <select name="item" id="">
                            <option value="all" <?php if (!empty($_POST)) {
                                                    if ($_POST['item'] == "all") {
                                                        echo "selected";
                                                    }
                                                } ?>>All</option>
                            <?php foreach ($item_names as $item) { ?>
                                <option <?php if (!empty($_POST)) {
                                            if ($_POST['item'] == $item['name']) {
                                                echo "selected";
                                            }
                                        } ?> value="<?php echo $item['name'] ?>"><?php echo $item['name'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div>
                    <div>Type</div>
                    <div>
                        <select name="type" id="">
                            <option value="all" <?php if (!empty($_POST)) {
                                                    if ($_POST['type'] == "all") {
                                                        echo "selected";
                                                    }
                                                } ?>>All</option>
                            <option value="balance" <?php if (!empty($_POST)) {
                                                        if ($_POST['type'] == "balance") {
                                                            echo "selected";
                                                        }
                                                    } ?>>Balance</option>
                            <option value="stock_in" <?php if (!empty($_POST)) {
                                                            if ($_POST['type'] == "stock_in") {
                                                                echo "selected";
                                                            }
                                                        } ?>>Stock In</option>
                            <option value="stock_out" <?php if (!empty($_POST)) {
                                                            if ($_POST['type'] == "stock_out") {
                                                                echo "selected";
                                                            }
                                                        } ?>>Stock Out</option>
                        </select>
                    </div>
                </div>

                <div>
                    <div>Date</div>
                    <!-- start date -->
                    <div class="print_report_input">
                        <!-- start date -->
                        <div class="start_date">
                            <input type="text" name="start_date" id="start_date" placeholder="start date" value="<?php echo $_POST['start_date'] ?? "" ?>">
                        </div>
                        <!-- end date -->
                        <div class="end_date">
                            <input type="text" name="end_date" id="end_date" placeholder="end date" value="<?php echo $_POST['end_date'] ?? "" ?>">
                        </div>
                    </div>
                    <!-- date input script -->
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

                <div>
                    <div>action</div>
                    <div class="retorts_get_button"><button type="submit" name="get_report">Get</button></div>
                </div>
            </form>

            <!-- all item details reports -->
            <?php if (isset($item_all)) { ?>
                <div class="item_all_details">
                    <?php if (!empty($item_all)) { ?>
                        <!-- titles -->
                        <div class="all_details_titles">
                            <div>Date</div>
                            <div>In</div>
                            <div>Out</div>
                            <div>Balance</div>
                        </div>

                        <!-- in_out_details -->
                        <?php foreach ($item_all as $stock) { ?>
                            <div class="in_out_details">
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
                            </div>
                        <?php } ?>

                        <!-- print report -->
                        <div class="items_balance_print">
                            <div onclick="download_report()">Download Report</div>
                        </div>
                    <?php } else { ?>
                        <div class="not_found_pane">
                            <div class="not_found_text">no records found to show</div>
                            <div class="not_found_image">
                                <img src="../../files/icons/not_found.png" alt="">
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>

            <!-- balances reports -->
            <?php if (isset($balances)) { ?>
                <div class="items_balance">
                    <?php if (!empty($balances)) { ?>
                        <!-- titles -->
                        <div class="items_balance_titles">
                            <div>Item</div>
                            <div>Balance</div>
                        </div>

                        <?php foreach ($balances as $balance) { ?>
                            <!-- item -->
                            <div class="items_balance_item">
                                <div><?php echo $balance['name'] ?></div>
                                <div><?php echo number_format($balance['balance']) ?></div>
                            </div>
                        <?php } ?>

                        <!-- total -->
                        <div class="items_balance_total">
                            Total Items: <span><?php echo count($balances) ?></span>
                        </div>

                        <!-- print report -->
                        <div class="items_balance_print">
                            <div onclick="download_report()">Download Report</div>
                        </div>
                    <?php } else { ?>
                        <div class="not_found_pane">
                            <div class="not_found_text">no items found to show</div>
                            <div class="not_found_image">
                                <img src="../../files/icons/not_found.png" alt="">
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>

            <!-- stock in reports -->
            <?php if (isset($stock_ins)) { ?>
                <div class="stock_in_reports">
                    <?php if (!empty($stock_ins)) { ?>
                        <!-- titles -->
                        <div class="stock_in_report_titles">
                            <div>Item</div>
                            <div>Quantity</div>
                            <div>Price per Unit</div>
                            <div>Checked By</div>
                            <div>Date</div>
                        </div>

                        <!-- item -->
                        <?php $stock_value = 0; ?>
                        <?php foreach ($stock_ins as $stock_in) { ?>
                            <?php
                            // calculate stock value
                            $value = (int)$stock_in['quantity'] * (float)$stock_in['price_per_unit'];
                            $stock_value += $value;
                            ?>
                            <div class="stock_in_report_item">
                                <div><?php echo $stock_in['item'] ?></div>
                                <div><?php echo number_format($stock_in['quantity']) ?></div>
                                <div>MK <?php echo number_format($stock_in['price_per_unit']) ?></div>
                                <div><?php echo $stock_in['checked_by'] ?></div>
                                <div><?php echo date("d M Y", strtotime($stock_in['created_at'])) ?></div>
                            </div>
                        <?php } ?>

                        <!-- total -->
                        <div class="items_balance_total">
                            Stock Value: <span>MK <?php echo number_format($stock_value) ?></span>
                        </div>

                        <!-- print report -->
                        <div class="items_balance_print">
                            <div onclick="download_report()">Download Report</div>
                        </div>
                    <?php } else { ?>
                        <div class="not_found_pane">
                            <div class="not_found_text">no records found to show</div>
                            <div class="not_found_image">
                                <img src="../../files/icons/not_found.png" alt="">
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>

            <!-- stock out reorts -->
            <?php if (isset($stock_outs)) { ?>
                <div class="stock_in_reports">
                    <?php if (!empty($stock_outs)) { ?>
                        <!-- titles -->
                        <div class="stock_in_report_titles">
                            <div>Item</div>
                            <div>Quantity</div>
                            <div>Requested By</div>
                            <div>Distributed By</div>
                            <div>Date</div>
                        </div>

                        <!-- item -->
                        <?php foreach ($stock_outs as $stock_out) { ?>
                            <div class="stock_in_report_item">
                                <div><?php echo $stock_out['item'] ?></div>
                                <div><?php echo number_format($stock_out['quantity']) ?></div>
                                <div><?php echo $stock_out['requested_by'] ?></div>
                                <div><?php echo $stock_out['distributed_by'] ?></div>
                                <div><?php echo date("d M Y", strtotime($stock_out['created_at'])) ?></div>
                            </div>
                        <?php } ?>

                        <!-- print report -->
                        <div class="items_balance_print">
                            <div onclick="download_report()">Download Report</div>
                        </div>
                    <?php } else { ?>
                        <div class="not_found_pane">
                            <div class="not_found_text">no records found to show</div>
                            <div class="not_found_image">
                                <img src="../../files/icons/not_found.png" alt="">
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </section>

    <script>
        function download_report() {
            document.location = "pdf.php";
        }
    </script>
</body>

</html>