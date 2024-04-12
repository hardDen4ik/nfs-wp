<?php
include('./phpqrcode/qrlib.php');
$url = $_GET['u'];

ob_start();
$debugLog = ob_get_contents();
ob_end_clean();

QRcode::png($url);