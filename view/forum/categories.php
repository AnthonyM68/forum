<?php

$categories = $result["data"]['categories'];

?>
<!-- ADD CATEGORY -->
<?php
if (isset($result['section']) && $result['section'] === "category") { ?>
    <div class="uk-grid-margin uk-grid uk-grid-stack" uk-grid>
        <div class="uk-width-1-2@m">
            <form action="./index.php?ctrl=forum&action=addCategory" method="post" class="uk-form-horizontal uk-margin-large">
                <div class="uk-margin">
                    <label class="uk-form-label" for="form-horizontal-text">Ajouter une nouvelle catégorie</label>
                    <div class="uk-form-controls">
                    <input name="token-hidden" type="hidden" value="<?= $_SESSION["token"] ?>">
                        <input name="name" class="uk-input" id="form-horizontal-text" type="text" placeholder="Nom de Catégorie">
                    </div>
                </div>
                <button type="submit" class="uk-text-center color-primary uk-button uk-button-default uk-button-large uk-width-1-1">Ajouter Catégorie</button>
            </form>
        </div>
    </div>
<?php 

} else { 

    ?>
    <!-- LIST CATEGORIES  -->
    <?php
    if ($categories) { ?>
        <h1 class="uk-animation-fade pridi-regular">Liste des Catégories</h1>
        <div class="uk-animation-fade uk-container">
            <div class="uk-grid-margin uk-grid uk-grid-stack" uk-grid>
                <div class="uk-width-1-1@m">
                    <ul>
                        <?php
                        foreach ($categories as $category) { ?>
                            <li>
                                <a class="uk-animation-fade pridi-light" href="index.php?ctrl=forum&action=listTopicsByCategory&id=<?= $category->getId() ?>">
                                    <?= $category->getName() ?>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <p class="color-primary">Aucune catégorie trouvée</p>
<?php }
}
?>