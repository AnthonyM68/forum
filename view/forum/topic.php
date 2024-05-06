<?php
use App\Session as Session;
// on récupère le topic principal
$topic = $result["data"]['topic'];
// on récupère tous les posts du topic
$posts = $result["data"]['posts'];
// le nom de controleur à utilisé
$ctrlname = "forum";
//on construit le namespace de la classe Controller à appeller
$ctrlNS = "controller\\" . ucfirst($ctrlname) . "Controller";
$ctrl = new $ctrlNS(); ?>


<h1 class="uk-animation-fade pridi-regular"></h1>

<!-- TOPIC -->
<div class="uk-card uk-card-default uk-card-body uk-margin-bottom">
    <div class="uk-grid-small uk-flex-middle" uk-grid>
        <div class="uk-width-auto">
            <img class="uk-border-circle" width="60" height="60" src="./public/img/profils/moi.jpg" alt="Avatar">
        </div>
        <div class="uk-width-expand">
            <h4 class="uk-card-title"><?= $topic->getUser()->getUsername() ?></h4>
            <h2><?= $topic->getTitle() ?></h2>
        </div>
    </div>
    <div class="uk-text-right">
        <?= $ctrl->convertToString($topic->getUser()->getRole()) ?>
    </div>
</div>

<!-- LIST POSTS -->
<?php
if ($posts) {
    foreach ($posts as $post) {?>
        <div class="uk-card uk-card-default uk-card-body uk-margin-bottom">
            <div class="uk-grid-small uk-flex-middle" uk-grid>
                <div class="uk-width-auto">
                    <img class="uk-border-circle" width="60" height="60" src="./public/img/profils/moi.jpg" alt="Avatar">
                </div>
                <div class="uk-width-expand">
                    <h4 class="uk-card-title"><?= $post->getTopic()->getUser()->getUsername() ?></h4>
                    <p><?= $post->getContent() ?></p>
                </div>
                <div class="uk-width-auto">
                    <a href="#" class="uk-icon-button uk-margin-small-right" uk-icon="icon: reply"
                        uk-tooltip="title: Répondre; pos: top-left"></a>
                    <a href="#" class="uk-icon-button uk-margin-small-right" uk-icon="icon: pencil"
                        uk-tooltip="title: Éditer; pos: top-left"></a>
                    <a href="#" class="uk-icon-button uk-margin-small-right" uk-icon="icon: trash"
                        uk-tooltip="title: Supprimer; pos: top-left"></a>
                    <a href="#" class="uk-icon-button uk-margin-small-right" uk-icon="icon: heart"
                        uk-tooltip="title: Like; pos: top-left">
                    </a>
                    <span>10</span>
                </div>
            </div>
            <div class="uk-text-right">
                <?= $ctrl->convertToString($post->getTopic()->getUser()->getRole()) ?>
                <?= $post->getDateCreation() ?>
            </div>
        </div>
    <?php }
    if(Session::getUser()) {
    if ($result['section'] === "edit-topic") { ?>
        <!-- TINYMCE -->
        <div class=" uk-column-1-1">
            <h3 class="color-primary">Editez votre réponse</h3>
            <form id="newCAt" name="newCat" action="./index.php?ctrl=forum&action=addPost&id=<?= $topic->getId() ?>" method="post"
                class="uk-form-horizontal uk-margin-large">
                <div class="uk-margin">
                    <textarea name="content" class="post">Votre article</textarea>
                </div>
                <input name="token-hidden" class="uk-input uk-form-large" type="text" value="<?= $_SESSION["token"] ?>" style="visibility:hidden">
                <input type="submit" class="uk-button uk-button-primary uk-button-large uk-width-1-1">
            </form>
        </div>
    <?php }   
    }else { ?>
        <p class="uk-text-center uk-text-danger uk-text-uppercase" uk-margin>
           Vous devez êtres inscrit pour répondre à ce Topic
       </p>
   <?php }     

}
