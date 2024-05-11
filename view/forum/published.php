<?php

$ctrlname = "forum";
//on construit le namespace de la classe Controller à appeller
$ctrlNS = "controller\\" . ucfirst($ctrlname) . "Controller";
$ctrl = new $ctrlNS();

$action = "findAllCategories";
$categories = $ctrl->$action();
?>


<!-- add category -->
<?php
if ($result['section'] === "post-edit") { 
    $post = $result['data']['post-edit'];
    ?>
    <!-- TINYMCE -->
    <div class=" uk-column-1-1">
        <h3 class="color-primary">Editez votre réponse</h3>
        <form id="add_post" name="add_post" action="./index.php?ctrl=forum&action=updatePost&id=<?= $post->getId() ?>" method="post" class="uk-form-horizontal uk-margin-large">
            <div class="uk-margin">
                <textarea name="content" class="post">Votre article</textarea>
            </div>
            <input name="token-hidden" class="uk-input uk-form-large" type="text" value="<?= $_SESSION["token"] ?>" style="visibility:hidden">
            <input type="submit" class="uk-button uk-button-primary uk-button-large uk-width-1-1">
        </form>
    </div>
<?php }
?>