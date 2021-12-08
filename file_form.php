<?php session_start();
if (empty($_SESSION['email'])) {
    header('Location: index.php');
}
?>
<!doctype html>

<html lang="en">
<head>
    <link href="index.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
    <title>Сайт объявлений Данилы Киренского</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

</head>
<body>
    <script src="scripts.js"></script>
    <header id="header" >
        <?php include 'session_check.php'?>
    </header>

    <main>
        <form name="load_file" action="load_ticket_to_DB.php"
              method="post" enctype="multipart/form-data">
            <label>Название: <input type="text" name="name" required></label>
            <label>Описание: <textarea name="description"></textarea></label>
            <label>Цена (в рублях): <input type="text" name="price" required></label>
            <label>Файл: <input type="file" name="photo" required></label>


            <input type="submit" name="send" value="Отправить">

        </form>

        <ul>
            <?php if (!empty($_SESSION['formErrors'])):
                 foreach ($_SESSION['formErrors'] as $error): ?>
                    <li><?= $error ?></li>
                <?php endforeach;
                unset($_SESSION['formErrors']);
                endif;?>
        </ul>

    </main>

    <footer>
        <div>Выполнил студент ПИ-19 Киренский Данила</div>
        <div>Email: Kirenskiydanila@gmail.com</div>
    </footer>

</body>
</html>


