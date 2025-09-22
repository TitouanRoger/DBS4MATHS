<?php
session_start();

$data = json_decode(file_get_contents("php://input"), true);
$fromRow = (int) $data['fromRow'];
$fromCol = (int) $data['fromCol'];
$toRow = (int) $data['toRow'];
$toCol = (int) $data['toCol'];

$board = $_SESSION['board'];
$turn = $_SESSION['turn'];

$piece = $board[$fromRow][$fromCol];

if ($piece == "_" || ($piece == "w" && $turn != "w") || ($piece == "b" && $turn != "b")) {
    echo json_encode(["success" => false, "message" => "Ce n'est pas ton tour !"]);
    exit;
}

$deltaRow = $toRow - $fromRow;
$deltaCol = $toCol - $fromCol;

function hasMandatoryCapture($board, $turn)
{
    for ($row = 0; $row < 8; $row++) {
        for ($col = 0; $col < 8; $col++) {
            $piece = $board[$row][$col];
            if (($turn == "w" && ($piece == "w" || $piece == "W")) || ($turn == "b" && ($piece == "b" || $piece == "B"))) {
                if (canCapture($board, $row, $col)) {
                    return true;
                }
            }
        }
    }
    return false;
}

function canCapture($board, $row, $col)
{
    $piece = $board[$row][$col];
    $directions = ($piece == "w" || $piece == "b") ? [[-2, -2], [-2, 2], [2, -2], [2, 2]] : [[-2, -2], [-2, 2], [2, -2], [2, 2]];

    foreach ($directions as $dir) {
        $midRow = $row + $dir[0] / 2;
        $midCol = $col + $dir[1] / 2;
        $newRow = $row + $dir[0];
        $newCol = $col + $dir[1];

        if ($newRow >= 0 && $newRow < 8 && $newCol >= 0 && $newCol < 8) {
            if ($board[$newRow][$newCol] == "_" && $board[$midRow][$midCol] != "_" && isOpponentPiece($piece, $board[$midRow][$midCol])) {
                return true;
            }
        }
    }
    return false;
}

function isOpponentPiece($piece, $targetPiece)
{
    if ($piece == "w" || $piece == "W") {
        return $targetPiece == "b" || $targetPiece == "B";
    } elseif ($piece == "b" || $piece == "B") {
        return $targetPiece == "w" || $targetPiece == "W";
    }
    return false;
}

if (hasMandatoryCapture($board, $turn) && abs($deltaRow) != 2) {
    echo json_encode(["success" => false, "message" => "Tu dois capturer si possible !"]);
    exit;
}

if (abs($deltaRow) == 1 && abs($deltaCol) == 1 && $board[$toRow][$toCol] == "_") {
    if (($piece == "w" && $deltaRow == -1) || ($piece == "b" && $deltaRow == 1) || $piece == "W" || $piece == "B") {
        $board[$toRow][$toCol] = $piece;
        $board[$fromRow][$fromCol] = "_";
    } else {
        echo json_encode(["success" => false, "message" => "Déplacement invalide !"]);
        exit;
    }
} elseif (abs($deltaRow) == 2 && abs($deltaCol) == 2) {
    $middleRow = ($fromRow + $toRow) / 2;
    $middleCol = ($fromCol + $toCol) / 2;

    if ($board[$middleRow][$middleCol] != "_" && isOpponentPiece($piece, $board[$middleRow][$middleCol]) && $board[$toRow][$toCol] == "_") {
        $board[$toRow][$toCol] = $piece;
        $board[$fromRow][$fromCol] = "_";
        $board[$middleRow][$middleCol] = "_";

        if (canCapture($board, $toRow, $toCol)) {
            $_SESSION['board'] = $board;
            echo json_encode(["success" => true, "message" => "Tu peux capturer encore !"]);
            exit;
        }
    } else {
        echo json_encode(["success" => false, "message" => "Mouvement invalide !"]);
        exit;
    }
} else {
    echo json_encode(["success" => false, "message" => "Mouvement non autorisé !"]);
    exit;
}

if ($piece == "w" && $toRow == 0)
    $board[$toRow][$toCol] = "W";
if ($piece == "b" && $toRow == 7)
    $board[$toRow][$toCol] = "B";

$_SESSION['turn'] = $turn == "w" ? "b" : "w";
$_SESSION['board'] = $board;

function checkWin($board)
{
    $whiteExists = false;
    $blackExists = false;

    foreach ($board as $row) {
        foreach ($row as $cell) {
            if ($cell == 'w' || $cell == 'W')
                $whiteExists = true;
            if ($cell == 'b' || $cell == 'B')
                $blackExists = true;
        }
    }

    if (!$whiteExists)
        return "Noir a gagné !";
    if (!$blackExists)
        return "Blanc a gagné !";
    return null;
}

$winner = checkWin($board);

if ($winner) {
    echo json_encode(["success" => true, "winner" => $winner]);
    session_destroy();
    exit;
}

echo json_encode(["success" => true]);
?>