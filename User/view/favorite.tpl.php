<?php
if (!isset($_SESSION['count'])) {
    $_SESSION['count'] = 0;
}
$_SESSION['count'] = $_SESSION['count'] + 1;
echo "Ввошел " . $_SESSION['count'] . " подряд";