<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= $meta_description ?>">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tiny.cloud/1/zg3mwraazn1b2ezih16je1tc6z7gwp5yd4pod06ae5uai8pa/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" integrity="sha256-h20CPZ0QyXlBuAw7A+KluUYx/3pK+c7lYEpqLTlxjYQ=" crossorigin="anonymous" />
    <link rel="stylesheet" href="<?= PUBLIC_DIR ?>/css/font-pridi.css">
    <link rel="stylesheet" href="<?= PUBLIC_DIR ?>/css/uikit.min.css">
    <link rel="stylesheet" href="<?= PUBLIC_DIR ?>/css/style.css">
    <title>FORUM</title>
</head>

<body>
    <header id="header">
        <div class="uk-container">
            <?= $header ?>
            <?= $news ?>
            <!-- c'est ici que les messages (erreur ou succès) s'affichent
                <h3 class="message uk-alert-danger"><?= App\Session::getFlash("error") ?></h3>
                <h3 class="message uk-alert-success"><?= App\Session::getFlash("success") ?></h3>-->
        </div>
    </header>
    <main id="forum">
        <div class="uk-container">
            <?= $page ?>
        </div>
    </main>
    <footer id="footer"><?= $footer ?></footer>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous">
    </script>
    <script src="<?= PUBLIC_DIR ?>/js/uikit.js"></script>
    <script src="<?= PUBLIC_DIR ?>/js/script.js"></script>
</body>

</html>