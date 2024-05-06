<!-- USERS LIST -->
<?php
if ($result['section'] === "usersList") { ?>
    <h1 class="uk-animation-fade pridi-regular">Liste des Membres</h1>
    <div class="uk-animation-fade uk-container">
        <div class="uk-grid-margin uk-grid uk-grid-stack" uk-grid>
            <div class="uk-width-1-1@m">
                <ul>
                    <?php
                    $users = $result["data"]['users'];
                    if ($users) {
                        foreach ($users as $user) { ?>
                            <li class="uk-animation-fade pridi-light">
                                    <span class="color-primary"><?= $user->getUsername() ?></span>
                                    
                                    <span class="color-secondary">Inscrit depuis: <?= $user->getDateRegister() ?></span>
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
    $restorAccount = false;
    if (isset($result['form']) && $result['form'] === "restorAccount") {
        $form = $result['form'];
        $restorAccount = true;
    }
    if ($user) { ?>
        <div class="uk-animation-fade uk-container">
            <div class="uk-grid-margin uk-grid uk-grid-stack" uk-grid>
                <div class="uk-width-1-1@m">
                    <h1 class="pridi-regular uk-animation-slide-top">Votre Profil <?= $user->getUsername() ?></h1>
                    <form
                        action="<?= $url = $restorAccount ? $url = "index.php?ctrl=security&action=restorAccount&token={$result['token']}" : $url = "index.php?ctrl=secutity&action=modifyAccount" ?>"
                        method="post" uk-grid>
                        <legend class="uk-legend color-secondary uk-animation-slide-top"></legend>
                        <div class="uk-width-1-2@s">
                            <div class="uk-margin">
                                <label class="uk-form-label" for="username">Nom d'utilisateur</label>
                                <input name="username" class="uk-input" type="text" value="<?= $user->getUsername() ?>"
                                    aria-label="Input">
                            </div>
                            <?php if ($restorAccount) { ?>
                                <p class="uk-text-center uk-text-danger uk-text-uppercase" uk-margin>
                                    Veuillez renouvellé votre mot de passe par mesure de sécurité
                                </p>
                            <?php } ?>
                            <div class="uk-grid-small" uk-grid>
                                <div class="uk-width-1-2@s">
                                    <label class="uk-form-label" for="password">Nouveau mot de passe</label>
                                    <div class="uk-form-controls">
                                        <input name="password" class="uk-input uk-width-1-1" type="password"
                                            autocomplete="cc-csc">
                                    </div>
                                </div>
                                <div class="uk-width-1-2@s">
                                    <label class="uk-form-label" for="repeat_password">Répéter mot de passe</label>
                                    <div class="uk-form-controls">
                                        <input name="repeat_password" class="uk-input uk-width-1-1" type="password"
                                            autocomplete="cc-csc">
                                    </div>
                                </div>
                            </div>
                            <div class="uk-margin">
                                <label class="uk-form-label" for="email">Email</label>
                                <input name="email" class="uk-input" type="text" value="<?= $user->getEmail() ?>"
                                    aria-label="Input">
                            </div>
                            <?php if (isset($result['form']) && $result['form'] === "account") { ?>
                                <div class="uk-grid-small uk-flex uk-flex-between" uk-grid>
                                    <p class="uk-text-center" uk-margin>
                                        <a class="uk-text-center uk-button uk-button-default"
                                            href="index.php?ctrl=security&action=modifyAccount&id=<?= $user->getId() ?>">Modifier
                                            mon compte</a>
                                    </p>
                                    <p class="uk-text-center" uk-margin>
                                        <a id="delete-account-btn"
                                            href="index.php?ctrl=security&action=deleteAccount&id=<?= $user->getId() ?>"
                                            data-id="<?= $user->getId(); ?>" class="uk-text-center uk-button uk-button-default"
                                            href="#">Supprimer mon compte</a>
                                    </p>
                                </div>
                            <?php } else { ?>
                                <div class="uk-grid-small uk-flex uk-flex-between" uk-grid>
                                    <p class="uk-text-center" uk-margin>
                                    <input name="token-hidden" class="uk-input uk-form-large" type="text" value="<?= $_SESSION["token"] ?>" style="visibility:hidden">
                                        <button type="submit" class="uk-text-center uk-button uk-button-default">Restaurer mon
                                            compte</button>
                                    </p>
                                </div>
                            <?php } ?>
                        </div>
                        <!-- second col -->
                    </form>
                </div>
            </div>
        </div>
    <?php }
}
