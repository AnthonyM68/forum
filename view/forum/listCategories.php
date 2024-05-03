<?php $categories = $result["data"]['categories']; ?>
<h1 class="uk-animation-fade pridi-regular">Liste des Catégories</h1>
<!-- CATEGORIES LIST -->
<?php
if ($categories) { ?>
    <div class="uk-animation-fade uk-container">
        <div class="uk-grid-margin uk-grid uk-grid-stack" uk-grid>
            <div class="uk-width-1-1@m">
                <ul>
                    <?php
                    foreach ($categories as $category) { ?>
                        <li>
                            <a class="uk-animation-fade pridi-light" href="index.php?ctrl=forum&action=listTopicsByCategory&id=<?= $category->getName() ?>">
                                <?= $category->getName() ?>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
<?php }
