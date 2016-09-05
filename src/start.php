<?php

include('classSync.php');

$sync = new Sync('localhost', 'alex', 'ooZ6re', 'alex', 'table_full.sql');
$sync->start_sync();

?>
