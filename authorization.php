<?php
session_start();
header('Content-Type: application/json');

$errors = [];

$email = filter_var($_POST['email'], FILTER_SANITIZE_SPECIAL_CHARS);
$password = filter_var($_POST['password'], FILTER_SANITIZE_SPECIAL_CHARS);

if (preg_match("/[a-zA-Z0-9]+@[a-zA-Z0-9]+\.[a-zA-Z0-9]+$/", $_POST['email']) === 0) {
    $errors[] = "Неверно введена электронная почта.";
}

include 'pdo.php';

$sql = "SELECT * FROM site_user WHERE email = :email";
$result = $pdo->prepare($sql);
$result->bindParam(':email', $_POST['email']);
$result->execute();
if ($result->rowCount() == 0) {
    $errors[] = 'Такого аккаунта не существует!';
}
$row = ($result->fetch(PDO::FETCH_ASSOC));

if (!empty($errors)) {
    echo json_encode(['errors' => $errors]);
    die();
}

if (!password_verify($password, $row['password'])) {
    $errors[] = 'Неверный пароль!';
    echo json_encode(['errors' => $errors]);
    die();
}

$_SESSION['email'] = $email;
$_SESSION['name'] = $row['user_name'];
$_SESSION['phone'] = $row['phone'];

echo json_encode(['success' => true]);
