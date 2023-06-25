<?php
session_start();
require_once 'dompdf/autoload.inc.php';

use Dompdf\Dompdf;

if (isset($_SESSION['report_array'])) {
    $report_array = $_SESSION['report_array'];

    $dompdf = new Dompdf();

    // css and headings
    $html = '<style>' . file_get_contents('./pdf.css') . '</style>';
    if ($_SESSION['report_type'] == "all") {
        // all
        $html .= '
                <div class="main_heading">DYUNI Stores Management</div>
                <div class="sub_heading">' . $_SESSION['item'] . ' Detailed Report</div>
            ';

        if ($_SESSION['start_date'] != "" && $_SESSION['end_date'] != "") {
            $html .= '
                    <div class="dates">
                        <div>From: <span>' . date("d M Y", strtotime($_SESSION['start_date'])) . '</span></div>
                        <div class="to">To: <span>' . date("d M Y", strtotime($_SESSION['end_date'])) . '</span></div>
                    </div>
                ';
        }

        if ($_SESSION['start_date'] == "" && $_SESSION['end_date'] != "") {
            $html .= '
                    <div class="dates">
                        <div>From: <span>-- -- --</span></div>
                        <div class="to">To: <span>' . date("d M Y", strtotime($_SESSION['end_date'])) . '</span></div>
                    </div>
                ';
        }

        if ($_SESSION['start_date'] != "" && $_SESSION['end_date'] == "") {
            $html .= '
                    <div class="dates">
                        <div>From: <span>' . date("d M Y", strtotime($_SESSION['start_date'])) . '</span></div>
                        <div class="to">To: <span>-- -- --</span></div>
                    </div>
                ';
        }

        $html .= '
            <table>
                <tr class="titles">
                    <th>Date</th>
                    <th>In</th>
                    <th>Out</th>
                    <th>Balance</th>
                </tr>
            ';

        foreach ($report_array as $stock) {
            if ($stock['in_balance'] != null) {
                $html .= '<tr class="item">';

                $html .= '
                    <td>
                        <div>' . date("d M Y", strtotime($stock['created_at'])) . '</div>
                        <div>
                            <div class="sub_title">Price / Unit</div>
                            <div>MK ' . number_format((float)$stock['price_per_unit']) . '</div>
                        </div>
                        <div>
                            <div class="sub_title">Checked By</div>
                            <div>' . $stock['checked_by'] . '</div>
                        </div>
                    </td>

                    <td>
                        <div>' . number_format($stock['quantity']) . '</div>
                        <div>
                            <div class="sub_title">Total Amount</div>
                            <div>MK ' . number_format((float)$stock['total_amount']) . '</div>
                        </div>
                        <div>
                            <div class="sub_title">Issued By</div>
                            <div>' . $stock['issued_by'] . '</div>
                        </div>
                    </td>

                    <td>
                        <div></div>
                        <div>
                            <div class="sub_title">Supplier</div>
                            <div>' . $stock['supplier'] . '</div>
                        </div>
                        <div>
                            <div class="sub_title">Remarks</div>
                            <div>' . $stock['remarks'] . '</div>
                        </div>
                    </td>

                    <td>
                        <div>' . number_format($stock['in_balance']) . '</div>
                        <div>
                            <div class="sub_title">Deliverd By</div>
                            <div>' . $stock['deliverd_by'] . '</div>
                        </div>
                        <div>
                            <div class="sub_title"></div>
                            <div></div>
                        </div>
                    </td>
                ';

                $html .= '</tr>';
            } else {
                $html .= '<tr class="item">';

                $html .= '
                    <td>
                        <div>' . date("d M Y", strtotime($stock['created_at'])) . '</div>
                        <div>
                            <div class="sub_title">Purpose</div>
                            <div>' . $stock['purpose'] . '</div>
                        </div>
                        <div>
                            <div class="sub_title"></div>
                            <div></div>
                        </div>
                    </td>

                    <td>
                        <div></div>
                        <div>
                            <div class="sub_title">Requested By</div>
                            <div>' . $stock['requested_by'] . '</div>
                        </div>
                        <div>
                            <div class="sub_title"></div>
                            <div></div>
                        </div>
                    </td>

                    <td>
                        <div>' . number_format($stock['quantity']) . '</div>
                        <div>
                            <div class="sub_title">Checked By</div>
                            <div>' . $stock['checked_by'] . '</div>
                        </div>
                        <div>
                            <div class="sub_title"></div>
                            <div></div>
                        </div>
                    </td>

                    <td>
                        <div>' . number_format($stock['out_balance']) . '</div>
                        <div>
                            <div class="sub_title">Distributed By</div>
                            <div>' . $stock['distributed_by'] . '</div>
                        </div>
                        <div>
                            <div class="sub_title"></div>
                            <div></div>
                        </div>
                    </td>
                ';

                $html .= '</tr>';
            }
        }

        $html .= '
                </table>
            ';
    } else if ($_SESSION['report_type'] == "balances") {
        // balances
        $html .= '
                <div class="main_heading">DYUNI Stores Management</div>
                <div class="sub_heading">Balances Report</div>
                ';

        $html .= '
                <table>
                    <tr class="titles">
                        <th>Item</th>
                        <th>Balance</th>
                    </tr>
                ';

        foreach ($report_array as $balance) {
            $html .= '
                    <tr class="item">
                        <td>' . $balance['name'] . '</td>
                        <td>' . number_format((float)$balance['balance']) . '</td>
                    </tr>
                ';
        }

        $html .= '
                </table>
            ';

        if ($_SESSION['item'] == "all") {
            $html .= '
                <div class="values">Total Items: <span>' . count($report_array) . '</span></div>
            ';
        }
    } else if ($_SESSION['report_type'] == "stock_in") {
        // stock in
        $html .= '
                <div class="main_heading">DYUNI Stores Management</div>
                <div class="sub_heading">Stock In Report</div>
                ';

        if ($_SESSION['start_date'] != "" && $_SESSION['end_date'] != "") {
            $html .= '
                    <div class="dates">
                        <div>From: <span>' . date("d M Y", strtotime($_SESSION['start_date'])) . '</span></div>
                        <div class="to">To: <span>' . date("d M Y", strtotime($_SESSION['end_date'])) . '</span></div>
                    </div>
                ';
        }

        if ($_SESSION['start_date'] == "" && $_SESSION['end_date'] != "") {
            $html .= '
                    <div class="dates">
                        <div>From: <span>-- -- --</span></div>
                        <div class="to">To: <span>' . date("d M Y", strtotime($_SESSION['end_date'])) . '</span></div>
                    </div>
                ';
        }

        if ($_SESSION['start_date'] != "" && $_SESSION['end_date'] == "") {
            $html .= '
                    <div class="dates">
                        <div>From: <span>' . date("d M Y", strtotime($_SESSION['start_date'])) . '</span></div>
                        <div class="to">To: <span>-- -- --</span></div>
                    </div>
                ';
        }

        $html .= '
                <table>
                    <tr class="titles">
                        <th>Item</th>
                        <th>Quantity</th>
                        <th>Price per Unit</th>
                        <th>Checked By</th>
                        <th>Date</th>
                    </tr>
                ';

        $stock_value = 0;
        foreach ($report_array as $stock_in) {
            $value = (int)$stock_in['quantity'] * (float)$stock_in['price_per_unit'];
            $stock_value += $value;
            $html .= '
                    <tr class="item">
                        <td>' . $stock_in['item'] . '</td>
                        <td>' . number_format((float)$stock_in['quantity']) . '</td>
                        <td>MK ' . number_format((float)$stock_in['price_per_unit']) . '</td>
                        <td>' . $stock_in['checked_by'] . '</td>
                        <td>' . date("d M Y", strtotime($stock_in['created_at'])) . '</td>
                    </tr>
                ';
        }

        $html .= '
                </table>
            ';

        $html .= '
            <div class="values">Inventory Value: MK <span>' . number_format($stock_value) . '</span></div>
        ';
    } else if ($_SESSION['report_type'] == "stock_out") {
        // stock out
        $html .= '
                <div class="main_heading">DYUNI Stores Management</div>
                <div class="sub_heading">Stock Out Report</div>
            ';

        if ($_SESSION['start_date'] != "" && $_SESSION['end_date'] != "") {
            $html .= '
                    <div class="dates">
                        <div>From: <span>' . date("d M Y", strtotime($_SESSION['start_date'])) . '</span></div>
                        <div class="to">To: <span>' . date("d M Y", strtotime($_SESSION['end_date'])) . '</span></div>
                    </div>
                ';
        }

        if ($_SESSION['start_date'] == "" && $_SESSION['end_date'] != "") {
            $html .= '
                    <div class="dates">
                        <div>From: <span>-- -- --</span></div>
                        <div class="to">To: <span>' . date("d M Y", strtotime($_SESSION['end_date'])) . '</span></div>
                    </div>
                ';
        }

        if ($_SESSION['start_date'] != "" && $_SESSION['end_date'] == "") {
            $html .= '
                    <div class="dates">
                        <div>From: <span>' . date("d M Y", strtotime($_SESSION['start_date'])) . '</span></div>
                        <div class="to">To: <span>-- -- --</span></div>
                    </div>
                ';
        }

        $html .= '
            <table>
                <tr class="titles">
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Requested By</th>
                    <th>Distributed By</th>
                    <th>Date</th>
                </tr>
            ';

        foreach ($report_array as $stock_out) {
            $html .= '
                    <tr class="item">
                        <td>' . $stock_out['item'] . '</td>
                        <td>' . number_format((float)$stock_out['quantity']) . '</td>
                        <td>' . $stock_out['requested_by'] . '</td>
                        <td>' . $stock_out['distributed_by'] . '</td>
                        <td>' . date("d M Y", strtotime($stock_out['created_at'])) . '</td>
                    </tr>
                ';
        }

        $html .= '
                </table>
            ';
    } else {
        header("location: reports.php");
    }

    // delete data
    // unset($_SESSION['report_array']);
} else {
    header("location: reports.php");
}

$dompdf->loadHtml($html);

// making the pdf
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$filename = uniqid();
$dompdf->stream("report_" . $filename . ".pdf");
