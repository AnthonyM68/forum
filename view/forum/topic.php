<?php $posts = $result["data"]['posts']; ?>

<h1 class="uk-animation-fade pridi-regular"></h1>

<?php
if ($posts) {
    foreach ($posts as $post) { ?>
        <div class="uk-card uk-card-default uk-card-body uk-margin-bottom">
            <div class="uk-grid-small uk-flex-middle" uk-grid>

                <div class="uk-width-auto">
                    <img class="uk-border-circle" width="60" height="60" src="./public/img/profils/moi.jpg" alt="Avatar">
                </div>
                <div class="uk-width-expand">
                    <h4 class="uk-card-title">Nom de l'utilisateur</h4>
                    <p><?= $post->getContent() ?></p>
                </div>

                <div class="uk-width-auto">
                <a href="#" class="uk-icon-button uk-margin-small-right" uk-icon="icon: reply" uk-tooltip="title: Répondre; pos: top-left"></a>
                    <a href="#" class="uk-icon-button uk-margin-small-right" uk-icon="icon: pencil" uk-tooltip="title: Éditer; pos: top-left"></a>
                    <a href="#" class="uk-icon-button uk-margin-small-right" uk-icon="icon: trash" uk-tooltip="title: Supprimer; pos: top-left"></a>
                    <a href="#" class="uk-icon-button uk-margin-small-right" uk-icon="icon: heart" uk-tooltip="title: Like; pos: top-left"></a>
                    <span>10</span>
                    
                </div>
                
                
            </div>
            <div><?= $post->getDateCreation() ?></div>

        </div>

    <?php }
    if ($result['section'] === "edit-topic") { ?>

        <div class=" uk-column-1-1">
            <h3 class="color-primary">Editez votre réponse</h3>
            <form id="newCAt" name="newCat" action="./index.php?ctrl=forum&action=addPost" method="post" class="uk-form-horizontal uk-margin-large">
                <div class="uk-margin">
                    <textarea name="content" class="post">Votre article</textarea>
                </div>
                <input type="submit" class="uk-button uk-button-primary uk-button-large uk-width-1-1">
            </form>
        </div>
<?php }
}
