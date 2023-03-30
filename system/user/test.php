<?php
session_start();
require_once 'dompdf/autoload.inc.php';

use Dompdf\Dompdf;

$dompdf = new Dompdf();

// pdf content
// css and headings
$html = '<style>' . file_get_contents('./pdf.css') . '</style>';
$html .= '
    <div class="main_heading">DYUNI Stores Management</div>
    <div class="sub_heading">Balances Report</div>
    ';

// table data
$html .= '
    <table>
        <tr class="titles">
            <th>Item</th>
            <th>Balance</th>
        </tr>

        <tr class="item">
            <td>name</td>
            <td>20</td>
        </tr>
        <tr class="item">
            <td>name</td>
            <td>20</td>
        </tr>
    </table>
';

$dompdf->loadHtml($html);

// making the pdf
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("trying.pdf");
