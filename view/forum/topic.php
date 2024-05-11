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
            <?php
            // si l'utilisateur est connecté
            if (Session::getUser()) {
                if (
                    // s'il possède un rôle d'édition ou d'admnistration 
                    (Session::getUser()->hasRole("ROLE_EDITOR") || Session::getUser()->hasRole("ROLE_ADMIN"))
                    // ou encore s'il est l'auteur du post 
                    || Session::getUser()->getId() === $topic->getUser()->getId()
                    // on lui affiche les bouton de gestion du post
                ) {
                    // Si l'utilisateur connecté est l'auteur du topic
                    ?>
                    <a data-action="./index.php?ctrl=forum&action=editTopic&id=<?= $topic->getId() ?>&anchor=card-<?= $topic->getId() ?> " href="#" class="token-link uk-icon-button uk-margin-small-right" uk-icon="icon: pencil" uk-tooltip="title: Éditer; pos: top-left"></a>
                    <a data-action="./index.php?ctrl=forum&action=deleteTopicAndPosts&id=<?= $topic->getId() ?>" href="#" class="token-link uk-icon-button uk-margin-small-right uk-text-danger" uk-icon="icon: trash" uk-tooltip="title: Supprimer; pos: top-left"></a>
            <?php }
            }
            ?>
        </div>
        <div class="uk-text-right">
            <i>
                <?= $ctrl->convertToString($topic->getUser()->getRole()) ?>
                <?= $topic->getDateCreation() ?>
            </i>
        </div>
    </div>
    <?php
    // modifier un topic si l'utilisateur est l'auteur
    if (
        Session::getUser() &&
        Session::getUser()->getId() === $topic->getUser()->getId()
    ) {
        if (isset($result['topic']) && $result['topic']) { ?>
            <h4>Modifiez votre Topic</h4>
            <form id="post" action="./index.php?ctrl=forum&action=updateTopic&id=<?= $topic->getId() ?>" method="post" class="uk-form-horizontal uk-margin-large">
                <div class="uk-margin">
                    <textarea name="content" class="post"><?= $topic->getTitle() ?></textarea>
                    <input type="submit" class="uk-button uk-button-success uk-button-large uk-width-1-1">
                    <input name="token-hidden" class="uk-input uk-form-large" type="text" value="<?= $_SESSION["token"] ?>" style="visibility:hidden">
                </div>
            </form>
<?php }
    }
}
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
                    <?php
                    // si l'utilisateur est connecté
                    if (Session::getUser()) {
                        if (
                            // s'il possède un rôle d'édition ou d'admnistration 
                            (Session::getUser()->hasRole("ROLE_EDITOR") || Session::getUser()->hasRole("ROLE_ADMIN"))
                            // ou encore s'il est l'auteur du post 
                            || Session::getUser()->getId() === $post->getUser()->getId()
                            // on lui affiche les bouton de gestion du post
                        ) {
                            /*<a data-action="./index.php?ctrl=forum&action=replyPost&id=<?= $post->getId() ?>" href="#" class="token-link uk-icon-button uk-margin-small-right" uk-icon="icon: reply" uk-tooltip="title: Répondre; pos: top-left"></a>*/
                            // next database
                            ?>
                            <a data-action="./index.php?ctrl=forum&action=editPost&id=<?= $post->getId() ?>&anchor=card-<?= $post->getId() ?> " href="#" class="token-link uk-icon-button uk-margin-small-right" uk-icon="icon: pencil" uk-tooltip="title: Éditer; pos: top-left"></a>
                            <a data-action="./index.php?ctrl=forum&action=deletePost&id=<?= $post->getId() ?>" href="#" class="token-link uk-icon-button uk-margin-small-right uk-text-danger" uk-icon="icon: trash" uk-tooltip="title: Supprimer; pos: top-left"></a>
                    <?php }
                    }
                    // LIKE en développement  
                    ?>
                    <a href="#" class="uk-icon-button uk-margin-small-right uk-text-danger" uk-icon="icon: heart" uk-tooltip="title: Like; pos: top-left"></a>
                    <span>
                        10
                    </span>
                </div>
            </div>
            <div class="uk-text-right">
                <i>
                    <?= $ctrl->convertToString($post->getTopic()->getUser()->getRole()) ?>
                    <?= $post->getDateCreation() ?>
                </i>
            </div>
        </div>
        <?php
        // si l'utilisateur est connecté 
        if (Session::getUser()) {
            // et qu'il demande a modifier son propre post
            if (isset($result['edit']) && $result['edit'] && $_GET['anchor'] === "card-" . $post->getId()) { 
                // on place l'éditeur en dessous du post a édité ?>
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
    } 
    // endforeach 
    // on place l'éditeur à la fin du topic 
    // si l'utilisateur est connecté
    if (Session::getUser()) {
        // si la vue peut rendre l'édition d'un post au topic true
        if (isset($result['post']) && $result['post']) { ?>
        <!-- TINYMCE -->
            <form id="post" action="./index.php?ctrl=forum&action=replyTopic&id=<?= $topic->getId() ?>" method="post" class="uk-form-horizontal uk-margin-large">
                <div class="uk-margin">
                    <textarea name="content" class="post"></textarea>
                    <input type="submit" class="uk-button uk-button-success uk-button-large uk-width-1-1">
                    //CS
                    <input name="token-hidden" class="uk-input uk-form-large" type="text" value="<?= $_SESSION["token"] ?>" style="visibility:hidden">
                </div>
            </form>
        <?php
        }
    } else { 
        // si l'utilisateur n'est pas connecté on lui indique qu'il doit l'être pour répondre ?>
        <p class="uk-text-center uk-text-danger uk-text-uppercase" uk-margin>
            Vous devez êtres connecté pour répondre à ce Topic
        </p>
<?php }
}
