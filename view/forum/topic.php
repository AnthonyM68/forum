<?php

use App\Session as Session;
// le nom de controleur à utilisé
$ctrlname = "forum";
//on construit le namespace de la classe Controller à appeller
$ctrlNS = "controller\\" . ucfirst($ctrlname) . "Controller";
$ctrl = new $ctrlNS();

// on récupère le topic principal
$topic = $result["data"]['topic'];
// on récupère tous les posts du topic
$posts = $result["data"]['posts'];

?>


<h1 class="uk-animation-fade pridi-regular"></h1>
<?php if ($topic) { ?>
    <!-- TOPIC -->
    <div class="uk-card uk-card-default uk-card-body uk-margin-bottom">
        <div class="uk-grid-small uk-flex-middle" uk-grid>
            <div class="uk-width-auto">
                <img class="uk-border-circle" width="60" height="60" src="./public/img/profils/moi.jpg" alt="Avatar">
            </div>
            <div class="uk-width-expand">
                <h4 class="uk-card-title"><?= $topic->getUser()->getUsername() ?></h4>
                <h2><?= htmlspecialchars_decode($topic->getTitle()) ?></h2>
            </div>
        </div>
        <div class="uk-text-right">
            <?= $ctrl->convertToString($topic->getUser()->getRole()) ?>
            <?= $topic->getDateCreation() ?>
        </div>
    </div>
<?php }
?>
<!-- LIST POSTS -->
<?php
if ($posts) {
    foreach ($posts as $post) { ?>
        <div id="card-<?= $post->getId() ?>" class="uk-card uk-card-default uk-card-body uk-margin-bottom">

            <div class="uk-grid-small uk-flex-middle" uk-grid>
                <div class="uk-width-auto">
                    <img class="uk-border-circle" width="60" height="60" src="./public/img/profils/moi.jpg" alt="Avatar">
                </div>
                <div class="uk-width-expand">
                    <h4 class="uk-card-title"><?= $post->getUser()->getUsername() ?></h4>
                    <p><?= htmlspecialchars_decode($post->getContent()) ?></p>
                </div>
                <div class="uk-width-auto">
                    <?php if (Session::getUser()) {
                        // XSCF
                        ?>
                        <form id="token_form" method="post" action="./index.php?ctrl=forum&action=">
                            <input name="token-form-link" type="hidden" value="<?= $_SESSION["token"] ?>">
                        </form>

                        <a data-action="./index.php?ctrl=forum&action=replyPost&id=<?= $post->getId() ?>" href="#" class="token-link uk-icon-button uk-margin-small-right" uk-icon="icon: reply" uk-tooltip="title: Répondre; pos: top-left"></a>
                        <?php
                        // Si l'utilisateur connecté est l'auteur du topic
                        if (Session::getUser()->getId() === $post->getUser()->getId()) { // modifier topic
                        ?>
                            <a data-action="./index.php?ctrl=forum&action=editPost&id=<?= $post->getId() ?>&anchor=card-<?= $post->getId() ?> " href="#" class="token-link uk-icon-button uk-margin-small-right" uk-icon="icon: pencil" uk-tooltip="title: Éditer; pos: top-left"></a>

                            <a data-action="./index.php?ctrl=forum&action=deletePost&id=<?= $post->getId() ?>" href="#" class="token-link uk-icon-button uk-margin-small-right" uk-icon="icon: trash" uk-tooltip="title: Supprimer; pos: top-left"></a>
                    <?php }
                    }
                    // LIKE en développement  
                    ?>
                    <a href="#" class="uk-icon-button uk-margin-small-right" uk-icon="icon: heart" uk-tooltip="title: Like; pos: top-left"></a>
                    <span>
                        10
                    </span>
                </div>
            </div>
            <div class="uk-text-right">
                <?= $ctrl->convertToString($post->getTopic()->getUser()->getRole()) ?>
                <?= $post->getDateCreation() ?>
            </div>
        </div>
        <?php
        // si l'utilisateur est connecté 
        if (Session::getUser()) {
            // et qu'il demande a modifier son propre post
            if (isset($result['edit']) && $result['edit'] && $_GET['anchor'] === "card-" . $post->getId()) { ?>
                <!-- TINYMCE -->
                <form id="reply-<?= $post->getTopic()->getId() ?>" action="./index.php?ctrl=forum&action=updatePost&id=<?= $post->getId() ?>" method="post" class="uk-form-horizontal uk-margin-large">
                    <div class="uk-margin">
                        <textarea name="content" class="post"><?= $post->getContent() ?></textarea>
                        <input type="submit" class="uk-button uk-button-success uk-button-large uk-width-1-1">
                        <input name="token-hidden" class="uk-input uk-form-large" type="text" value="<?= $_SESSION["token"] ?>" style="visibility:hidden">
                    </div>
                </form>
            <?php }
        }
    } // endforeach 
    if (Session::getUser()) {
        if (isset($result['post']) && $result['post']) { ?>
            <form id="post" action="./index.php?ctrl=forum&action=replyTopic&id=<?= $topic->getId() ?>" method="post" class="uk-form-horizontal uk-margin-large">
                <div class="uk-margin">
                    <textarea name="content" class="post"></textarea>
                    <input type="submit" class="uk-button uk-button-success uk-button-large uk-width-1-1">
                    <input name="token-hidden" class="uk-input uk-form-large" type="text" value="<?= $_SESSION["token"] ?>" style="visibility:hidden">
                </div>
            </form>
        <?php
        }
    } else { ?>
        <p class="uk-text-center uk-text-danger uk-text-uppercase" uk-margin>
            Vous devez êtres connecté pour répondre à ce Topic
        </p>
<?php }
}
