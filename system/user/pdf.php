<?php
session_start();
require_once 'dompdf/autoload.inc.php';

use Dompdf\Dompdf;

if (isset($_SESSION['report_array'])) {
    $report_array = $_SESSION['report_array'];

    $dompdf = new Dompdf();

    // css and headings
    $html = '<style>' . file_get_contents('./pdf.css') . '</style>';

    if ($_SESSION['report_type'] == "balances") {
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
                        <td>'.$balance['name'].'</td>
                        <td>'.number_format((float)$balance['balance']).'</td>
                    </tr>
                ';
        }

        $html .= '
                </table>
            ';

        if($_SESSION['item'] == "all"){
            $html .= '
                <div class="values">Total Items: <span>'.count($report_array).'</span></div>
            ';            
        }

    } else if ($_SESSION['report_type'] == "stock_in") {
        // stock in
        $html .= '
                <div class="main_heading">DYUNI Stores Management</div>
                <div class="sub_heading">Stock In Report</div>
                ';
    } else if ($_SESSION['report_type'] == "stock_out") {
        // stock out
        $html .= '
                <div class="main_heading">DYUNI Stores Management</div>
                <div class="sub_heading">Stock Out Report</div>
                ';
    } else {
        header("location: index.php");
    }

    // delete data
    // unset($_SESSION['report_array']);
} else {
    header("location: index.php");
}

$dompdf->loadHtml($html);

// making the pdf
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("trying.pdf");
