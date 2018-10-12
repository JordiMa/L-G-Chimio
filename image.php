<?php
include('class/codeBarreC128.class.php');
$code = new codeBarreC128($_GET['codeB']);
$code->setTitle();
$code->setFramedTitle(true);
$code->setHeight(40);
$code->Output();
?>