<?php
$categories = $result["data"]['categories'];
?>

<h1 class="uk-animation-fade pridi-regular">Catégories</h1>

<?php
if ($categories) {
    foreach ($categories as $category) { ?>
<div class="uk-margin">
    <!--<div class="uk-card uk-card-default uk-card-small">
        <div class="uk-card-body">
            <div class="uk-grid-small uk-flex-middle" uk-grid>
                 Contenu de la carte -->
                <div class="uk-padding uk-width-expand">
                    <h4 class="uk-card-title">
                        <a class="uk-animation-fade pridi-light" href="index.php?ctrl=forum&action=listTopicsByCategory&id=<?= $category->getId() ?>"><?= $category->getName() ?></a>
                    </h4>
                </div>
            <!--</div>
        </div>
    </div>-->
</div>


<?php }
}
