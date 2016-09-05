<?php

include('classSync.php');

$sync = new Sync('localhost', 'alex', '123456', 'alex', 'table_full.sql');
$sync->start_sync();

?>
