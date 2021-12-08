<?php
include "pdo.php";
$sql = "SELECT * FROM ticket WHERE is_closed = false AND id >= :id limit 1";
$result = $pdo->prepare($sql);
$result->bindParam(':id', $id, PDO::PARAM_INT);
$result->execute();
echo $result->rowCount();
