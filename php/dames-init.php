<?php
session_start();

$board = array_fill(0, 8, array_fill(0, 8, "_"));

for ($row = 0; $row < 3; $row++) {
    for ($col = 0; $col < 8; $col++) {
        if (($row + $col) % 2 == 1) {
            $board[$row][$col] = "b";
        }
    }
}

for ($row = 5; $row < 8; $row++) {
    for ($col = 0; $col < 8; $col++) {
        if (($row + $col) % 2 == 1) {
            $board[$row][$col] = "w";
        }
    }
}

$_SESSION['board'] = $board;
$_SESSION['turn'] = "w";

header("Location: ../session.php?page=dames");
exit;
?>