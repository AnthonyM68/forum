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
<?php
if ($result['section'] === "profile") { ?>
    <div class="uk-animation-fade uk-container">
        <div class="uk-grid-margin uk-grid uk-grid-stack" uk-grid>
            <div class="uk-width-1-1@m">
                <h1 class="uk-animation-fade pridi-regular">Votre Profil</h1>
                <ul>
                    <?php
                    if (isset($result['data']['users'])) {
                        foreach ($result['data']['users'] as $user) {
                    ?>
                        <li class="uk-animation-fade"><?= /*$user->getUsername()*/"" ?> Inscrit depuis: <?= /*$user->getDateRegister()*/ ""?></li>
                    <?php }
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
<?php } ?>