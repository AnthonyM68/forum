<?php
$categories = $result["data"]['categories'];
?>

<h1 class="uk-animation-fade pridi-regular">CATÉGORIES</h1>

<?php
if ($categories) {
    foreach ($categories as $category) { ?>
        <p><a class="uk-animation-fade pridi-light" href="index.php?ctrl=forum&action=listTopicsByCategory&id=<?= $category->getId() ?>"><?= $category->getName() ?></a></p>
<?php }
}
