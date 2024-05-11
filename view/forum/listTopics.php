<?php
//$category = $result["data"]['category'];
$topics = $result["data"]['topics'];
?>

<h1 class="pridi-regular">Liste des topics</h1>

<?php
if ($topics) {
    foreach ($topics as $topic) { ?>
        <p>
            <a href="index.php?ctrl=forum&action=showFullTopic&id=<?= $topic->getId() ?>&anchor=card-<?= $topic->getId() ?>"><?= $topic->getTitle() ?></a>
            Ouvert par: <span class="fas fa-user"></span>
            <span class="color-link"><?= $topic->getUser()->getUsername() ?></span>
            <?= $ctrl->convertToString($topic->getUser()->getRole()) ?>
        </p>
    <?php }
} else { ?>
    <p class="color-primary">Aucun Topics dans cette catégorie</p>
<?php }
