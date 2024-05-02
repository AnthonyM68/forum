<?php


?>


<!-- USERS LIST -->
<?php
if ($result['section'] === "usersList") { ?>
    <div class="uk-animation-fade uk-container">
        <div class="uk-grid-margin uk-grid uk-grid-stack" uk-grid>
            <div class="uk-width-1-1@m">
                <h1 class="uk-animation-fade pridi-regular">Liste des Membres</h1>
                <ul>
                    <?php
                    if (isset($result['data']['users'])) {
                        foreach ($result['data']['users'] as $user) {
                    ?>
                            <li class="uk-animation-fade"><?= $user->getUsername() ?> Inscrit depuis: <?= $user->getDateRegister() ?></li>
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
    <div class="uk-animation-fade uk-container">
        <div class="uk-grid-margin uk-grid uk-grid-stack" uk-grid>
            <div class="uk-width-1-1@m">
                <h1 class="uk-animation-fade pridi-regular uk-animation-slide-top">Votre Profil</h1>
                <form uk-grid>
                    <?php
                    // on recherche l'utilisateur en $_SESSION 
                    $user = $result['data']['user']; ?>
                    <legend class="uk-legend color-secondary uk-animation-slide-top"><?= $user ? $user->getUsername() : "" ?></legend>

                    <div class="uk-width-1-2@s">
                        <div class="uk-margin">
                            <input name="username" class="uk-input" type="text" value="<?= $user ? $user->getUsername() : "" ?>" aria-label="Input">
                        </div>
                        <div class="uk-grid-small" uk-grid>
                            <div class="uk-width-1-2@s">
                                <span class="uk-form-icon uk-form-icon-flip" uk-icon="icon: lock"></span>
                                <input name="password" class="uk-input" type="password" placeholder="Nouveau mot de passe" aria-label="Not clickable icon">
                            </div>
                            <div class="uk-width-1-2@s">
                                <span class="uk-form-icon uk-form-icon-flip" uk-icon="icon: lock"></span>
                                <input name="repeat_password" class="uk-input" type="password" placeholder="Répéter nouveau mot de passe" aria-label="Not clickable icon">
                            </div>
                        </div>
                        <div class="uk-margin">
                            <input name="email" class="uk-input" type="text" value="<?= $user ? $user->getEmail() : "" ?>" aria-label="Input">
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
<?php } ?>