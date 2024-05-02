<?php
//$category = $result["data"]['category'];
$topics = $result["data"]['topics'];
?>

<h1 class="pridi-regular">Liste des topics</h1>

<?php
if ($topics) {
    foreach ($topics as $topic) { ?>
        <p><a href="#"><?= $topic ?></a> par <?= $topic->getUser()->getEmail() ?></p>

<?php }
}
