<?php

use Dompdf\Dompdf;

session_start();

if (isset($_POST['print_report'])) {
    require "./vendor/autoload.php";
}

class Welcome{
    use Dompdf;
    public function __construct(){
        require "./vendor/autoload.php";
    }
}
