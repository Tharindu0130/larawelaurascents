<?php
$lines = file('storage/framework/maintenance.php');
foreach ($lines as $i => $line) {
    printf("%3d: %s", $i+1, rtrim($line, "\r\n")).PHP_EOL;
}
