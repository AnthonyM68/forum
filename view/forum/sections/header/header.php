<!-- NAVIGATION -->
<nav class="uk-navbar-container uk-animation-fade">
    <div uk-navbar>
        <div class="uk-navbar-right ">
            <ul class="uk-navbar-nav pridi-semibold">
                <li>
                    <a href="./"><span class="fas fa-home"></span>
                        Accueil
                    </a>
                </li>
                <li>
                    <a href="index.php?ctrl=forum&action=index">
                        <span class="fas fa-list-ol"></span>
                        Liste des catégories</a>
                </li>
                <?php
                // Si ADMINISTRATOR
                if (App\Session::isAdmin()) { ?>
                    <li>
                        <a href="index.php?ctrl=home&action=users">
                            <span class="fas fa-list-ol"></span>
                            Voir la liste des gens
                        </a>
                    </li>
                    <li>
                        <a href="index.php?ctrl=forum&action=addCategory">
                            <span class="fa fa-plus"></span>
                            Ajouter une catégorie
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>
<!-- navbar $_SESSION + addTopic addPost -->
<nav class="uk-navbar-container uk-animation-fade">
    <div class="uk-container">
        <div uk-navbar>
            <div class="uk-navbar-left">
                <ul class="uk-navbar-nav">
                    <?php
                    // si l'utilisateur est connecté 
                    if (App\Session::getUser()) {
                    ?>
                        <!--<a href="index.php?ctrl=security&action=profile"><span class="fas fa-user"><?= App\Session::getUser() ?></span></a>-->
                        <li>
                            <a href="./"><span class="fas fa-user"></span><?= App\Session::getUser() ?>
                            </a>
                        </li>
                        <li>
                            <a href="index.php?ctrl=security&action=logout">
                                <span class="fas fa-sign-out-alt"></span>
                                Déconnexion
                            </a>
                        </li>
                        <li>
                            <a href="index.php?ctrl=forum&action=addTopic">
                                <span class="fa fa-plus"> </span>
                                Nouveaux Topics
                            </a>
                        </li>
                        <li>
                            <a href="index.php?ctrl=forum&action=addPost">
                                <span class="fa fa-plus"></span>
                                Nouveaux Messages
                            </a>
                        </li>
                    <?php
                    } else {
                    ?>
                        <li>
                            <a id="login-link" href="index.php?ctrl=security&action=login">
                                Connexion
                            </a>
                        </li>
                        <li>
                            <a id="register-link" href="index.php?ctrl=security&action=register">
                                Inscription
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
            <div class="uk-navbar-right">
                <ul class="uk-navbar-nav">
                    <li><input class="uk-search-input" type="search" aria-label="…"></li>
                </ul>
            </div>
        </div>
    </div>
</nav>