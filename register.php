<?php
session_start();
if (empty($_SESSION['email'])) {
    header('Location: index.php');
}
header('Content-Type: application/json');

$errors = [];

$email = filter_var($_POST['email'], FILTER_SANITIZE_SPECIAL_CHARS);
$name = filter_var($_POST['name'], FILTER_SANITIZE_SPECIAL_CHARS);
$phone = filter_var($_POST['tel'], FILTER_SANITIZE_SPECIAL_CHARS);
$password = filter_var($_POST['firstPassword'], FILTER_SANITIZE_SPECIAL_CHARS);
$hash = password_hash($password, PASSWORD_DEFAULT);

if (preg_match("/[А-ЯЁРХЭЮЬЧа-яёрьхъюэч]+$/", $_POST['name']) === 0) {
    $errors[] = $_POST['name'];
    $errors[] = "Имя должно содержать только кириллицу.";
}

if (preg_match("/[a-zA-Z0-9]+@[a-zA-Z0-9]+\.[a-zA-Z0-9]+$/", $_POST['email']) === 0) {
    $errors[] = "Неверно введена электронная почта.";
}

if (preg_match("/[\+]\d{11}/", $_POST['tel']) === 0) {
    $errors[] = "Неверный формат телефона.";
}

if (strlen($_POST['firstPassword']) < 7) {
    $errors[] = "Слишком короткий пароль.";
}

if (preg_match("/[@?!,.a-zA-Z0-9\s]+$/", $_POST['firstPassword']) === 0) {
    $errors[] = "Неверный формат пароля (Допустимы только английские буквы, цифры, символы ' @ ? ! , . ' .";
}

if ($_POST['firstPassword'] != $_POST['secondPassword']) {
    $errors[] = "Пароли не одинаковы!";
}


if (!empty($errors)) {
    echo json_encode(['errors' => $errors]);
    die();
}

include 'pdo.php';

$sql = "SELECT * FROM site_user WHERE email = :email OR phone = :phone limit 1";
$result = $pdo->prepare($sql);
$result->bindParam(':email', $_POST['email']);
$result->bindParam(':phone', $_POST['tel']);
$result->execute();
if ($result->rowCount() != 0) {
    $errors[] = 'Аккаунт с таким номером телефона или электронной почтой уже существует!';
}

if (!empty($errors)) {
    echo json_encode(['errors' => $errors]);
    die();
}

$sql = "INSERT INTO site_user(phone, email, user_name, password) VALUES (:phone, :email, :user_name, :hash)";
$result = $pdo->prepare($sql);
$result->bindParam(':email', $email);
$result->bindParam(':phone', $phone);
$result->bindParam(':user_name', $name);
$result->bindParam(':hash', $hash);
$result->execute();

$_SESSION['email'] = $email;
$_SESSION['name'] = $name;
$_SESSION['phone'] = $phone;

echo json_encode(['success' => true]);
