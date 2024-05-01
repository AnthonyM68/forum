<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= $meta_description ?>">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- TINYMCE -->
    <script src="https://cdn.tiny.cloud/1/zg3mwraazn1b2ezih16je1tc6z7gwp5yd4pod06ae5uai8pa/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" integrity="sha256-h20CPZ0QyXlBuAw7A+KluUYx/3pK+c7lYEpqLTlxjYQ=" crossorigin="anonymous" />
    <!-- FONT -->
    <link rel="stylesheet" href="<?= PUBLIC_DIR ?>/css/font-pridi.css">
    <!-- UIKIT -->
    <link rel="stylesheet" href="<?= PUBLIC_DIR ?>/css/uikit.min.css">
    <!-- STYLE -->
    <link rel="stylesheet" href="<?= PUBLIC_DIR ?>/css/style.css">
    <title>FORUM</title>
</head>

<body>
    <!-- HEADER -->
    <header id="header">

        <div class="uk-container">
            <!-- NAVIGATION -->
            <?= $header ?>
            <!-- ALERT -->
            <?php if (App\Session::ifExistFlash("success")) { ?>
                <div class="message uk-alert-success" uk-alert>
                    <h3><?= App\Session::getFlash("success") ?></h3>
                </div>
            <?php }
            if (App\Session::ifExistFlash("warning")) { ?>
                <div class="message uk-alert-warning" uk-alert>
                    <h3><?= App\Session::getFlash("warning") ?></h3>
                </div>
            <?php }
            if (App\Session::ifExistFlash("error")) { ?>
                <div class="message uk-alert-danger" uk-alert>
                    <h3><?= App\Session::getFlash("error") ?></h3>
                </div>
            <?php }
            ?>
            <!-- NEWS TOPIC NEW POST -->
            <?= $news ?>
        </div>
    </header>
    <!-- MAIN -->
    <main id="forum">
        <div class="uk-container"><?= $page ?></div>
    </main>
    <footer id="footer"><?= $footer ?></footer>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous">
    </script>
    <!-- ICONS UIKIT -->
    <script src="<?= PUBLIC_DIR ?>/js/uikit-icons.min.js"></script>
    <!-- UIKIT -->
    <script src="<?= PUBLIC_DIR ?>/js/uikit.js"></script>
    <!-- SCRIPT JS -->
    <script src="<?= PUBLIC_DIR ?>/js/script.js"></script>
</body>

</html>