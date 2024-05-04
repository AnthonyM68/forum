<!-- USERS LIST -->
<?php
if ($result['section'] === "usersList") { ?>
    <h1 class="uk-animation-fade pridi-regular">Liste des Membres</h1>
    <div class="uk-animation-fade uk-container">
        <div class="uk-grid-margin uk-grid uk-grid-stack" uk-grid>
            <div class="uk-width-1-1@m">
                <ul>
                    <?php
                    $users = $result["data"]['user'];
                    if ($users) {
                        foreach ($users as $user) { ?>
                            <li><a class="uk-animation-fade pridi-light">
                                    <?= $user->getUsername() ?>
                                    Inscrit depuis:
                                    <?= $user->getDateRegister() ?>
                            </li>
                    <?php }
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
<?php } ?>
<!-- PROFIL USER -->
<?php
if ($result['section'] === "profile") { ?>
    <?php
    // on recherche l'utilisateur en $_SESSION 
    $user = $result['data']['user'];
    if ($user) { ?>
        <div class="uk-animation-fade uk-container">
            <div class="uk-grid-margin uk-grid uk-grid-stack" uk-grid>
                <div class="uk-width-1-1@m">
                    <h1 class="pridi-regular uk-animation-slide-top">Votre Profil <?= $user->getUsername() ?></h1>
                    <form action="index.php?ctrl=security&action=modifyAccount" method="post" uk-grid>
                        <legend class="uk-legend color-secondary uk-animation-slide-top"></legend>
                        <div class="uk-width-1-2@s">
                            <div class="uk-margin">
                                <label class="uk-form-label" for="username">Nom d'utilisateur</label>
                                <input name="username" class="uk-input" type="text" value="<?= $user->getUsername() ?>" aria-label="Input">
                            </div>
                            <div class="uk-grid-small" uk-grid>
                                <div class="uk-width-1-2@s">
                                    <label class="uk-form-label" for="password">Nouveau mot de passe</label>
                                    <div class="uk-form-controls">
                                        <input name="password" class="uk-input uk-width-1-1" type="password">
                                    </div>
                                </div>
                                <div class="uk-width-1-2@s">
                                    <label class="uk-form-label" for="repeat_password">Répéter mot de passe</label>
                                    <div class="uk-form-controls">
                                        <input name="repeat_password" class="uk-input uk-width-1-1" type="password">
                                    </div>
                                </div>
                            </div>
                            <div class="uk-margin">
                                <label class="uk-form-label" for="email">Email</label>
                                <input name="email" class="uk-input" type="text" value="<?= $user->getEmail() ?>" aria-label="Input">
                            </div>
                            <div class="uk-grid-small uk-flex uk-flex-between" uk-grid>
                                <p class="uk-text-center" uk-margin>
                                    <a class="uk-text-center uk-button uk-button-default" href="index.php?ctrl=security&action=modifyAccount&id=<?= $user->getId() ?>">Modifier mon compte</a>
                                </p>
                                <p class="uk-text-center" uk-margin>
                                    <a class="uk-text-center uk-button uk-button-default" href="index.php?ctrl=security&action=deleteAccount&id=<?= $user->getId() ?>">Supprimer mon compte</a>
                                </p>
                            </div>
                        </div>
                        <!-- second col -->
                    </form>
                </div>
            </div>
        </div>
    <?php }
}

if ($result['section'] === "delete-account") { ?>
    <?php
    // on recherche l'utilisateur en $_SESSION 
    $user = $result['data']['user'];
    if ($user) { ?>
        <div class="uk-animation-fade uk-container">
            <div class="uk-grid-margin uk-grid uk-grid-stack" uk-grid>
                <div class="uk-width-1-1@m">
                    <h1 class="pridi-regular uk-animation-slide-top">Confirmez la suppresion par mot de passe <?= $user->getUsername() ?></h1>
                    <form id="delete-account" action="index.php?ctrl=security&action=deleteAccount&id=<?= $user->getId() ?>" method="post" uk-grid>
                        <legend class="uk-legend color-secondary uk-animation-slide-top"></legend>

                        <div class="uk-width-1-2@s">
                            <div class="uk-grid-small" uk-grid>
                                <div class="uk-width-1-2@s">
                                    <label class="uk-form-label" for="password">Nouveau mot de passe</label>
                                    <div class="uk-form-controls">
                                        <input name="password" class="uk-input uk-width-1-1" type="password">
                                    </div>
                                </div>
                                <div class="uk-width-1-2@s">
                                    <label class="uk-form-label" for="repeat_password">Répéter mot de passe</label>
                                    <div class="uk-form-controls">
                                        <input name="repeat_password" class="uk-input uk-width-1-1" type="password">
                                    </div>
                                </div>
                            </div>

                            <button class="uk-margin uk-button uk-button-default">Soumettre</button>

                        </div>
                        <!-- second col -->
                    </form>
                </div>
            </div>
        </div>
<?php
    }
}
