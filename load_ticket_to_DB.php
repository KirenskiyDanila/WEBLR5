<?php
use Symfony\Component;
if (empty($_SESSION['email'])) {
    header('Location: index.php');
}
include_once 'MyValidator.php';

session_start();
header('Content-Type: application/json');

$errors = [];


$name = filter_var($_POST['name'], FILTER_SANITIZE_SPECIAL_CHARS);
if (!empty($_POST['description'])) {
    $description = filter_var($_POST['description'], FILTER_SANITIZE_SPECIAL_CHARS);
} else {
    $description = 'Описания нет.';
}
$price = filter_var($_POST['price'], FILTER_SANITIZE_SPECIAL_CHARS);

if (!empty($_FILES['photo'])) {
    $file = $_FILES['photo'];
} else {
    $errors [] = 'Файл не загружен!';
    $_SESSION['formErrors'] = $errors;
    header('Location: file_form.php');
    die();
}


$input = array();
$input['name'] = $name;
$input['price'] = $price;
$input['photo'] = $_FILES['photo']['tmp_name'];

$validator = new MyValidator();
$errors += $validator->validate($input);

if (!empty($errors)) {
    $_SESSION['formErrors'] = $errors;
    header('Location: file_form.php');
    die();
}

$pathInfo = pathinfo($_FILES['photo']['name']);
$ext = $pathInfo['extension'] ?? "";
$newPath = 'images' . "/" . uniqid() . "." . $ext;

if (move_uploaded_file($_FILES['photo']['tmp_name'], $newPath)) {
    include 'pdo.php';

    $sql = "INSERT INTO ticket(user_phone, photo, description, price, name)
VALUES (:phone, :photo, :description, :price, :name)
RETURNING id";
    $result = $pdo->prepare($sql);
    $result->bindParam(':phone', $_SESSION['phone']);
    $result->bindParam(':photo', $newPath);
    $result->bindParam(':description', $description);
    $result->bindParam(':price', $price);
    $result->bindParam(':name', $name);
    $result->execute();
    $row = ($result->fetch(PDO::FETCH_ASSOC));
    header('Location: ticket.php?id=' . $row['id']);
} else {
    $errors[] = "Возможная файловая атака!";
    $_SESSION['formErrors'] = $errors;
    header('Location: file_form.php');
    die();
}
