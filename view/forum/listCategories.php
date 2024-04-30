<?php
$categories = $result["data"]['categories'];
?>

<h1 class="uk-animation-fade pridi-regular">Liste des catégories</h1>

<?php
if ($categories) {
    foreach ($categories as $category) { ?>
        <p><a class="pridi-light" href="index.php?ctrl=forum&action=listTopicsByCategory&id=<?= $category->getId() ?>"><?= $category->getName() ?></a></p>
<?php }
}
