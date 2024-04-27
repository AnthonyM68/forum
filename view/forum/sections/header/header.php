
    <nav class="uk-navbar-container">
        <div uk-navbar>
            <div class="uk-navbar-right">
                <ul class="uk-navbar-nav pridi-semibold">

                    <li><a href="./">Accueil</a></li>
                    <li><a href="index.php?ctrl=forum&action=index">Liste des catégories</a></li>
                    <?php
                    if (App\Session::isAdmin()) { ?>
                        <a href="index.php?ctrl=home&action=users">Voir la liste des gens</a>
                    <?php } ?>
                </ul>

            </div>
        </div>

    </nav>

    <ul class="uk-subnav pridi-semibold">
        <?php
        // si l'utilisateur est connecté 
        if (App\Session::getUser()) {
        ?>
            <a href="index.php?ctrl=security&action=profile"><span class="fas fa-user"></span>&nbsp;<?= App\Session::getUser() ?></a>
            <a href="index.php?ctrl=security&action=logout">Déconnexion</a>
        <?php
        } else {
        ?>
            <li><a id="login-link" href="index.php?ctrl=security&action=login">Connexion</a></li>
            <li><a id="register-link" href="index.php?ctrl=security&action=register">Inscription</a></li>
        <?php } ?>
    </ul>
   
