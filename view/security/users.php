<div class="uk-animation-fade uk-container">
    <div class="uk-grid-margin uk-grid uk-grid-stack" uk-grid>
        <div class="uk-width-1-1@m">
            <h2 class="uk-animation-fade" >UTILISATEURS</h2>
            <ul>
                <?php
                foreach ($result['data']['users'] as $user) { 
                    ?>
                    <li class="uk-animation-fade" ><?= $user->getUsername() ?> Inscrit depuis: <?= $user->getDateRegister() ?></li>
                <?php }
                ?>
            </ul>
        </div>
    </div>
</div>