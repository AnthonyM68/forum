<?php

$ctrlname = "forum";
//on construit le namespace de la classe Controller à appeller
$ctrlNS = "controller\\" . ucfirst($ctrlname) . "Controller";
$ctrl = new $ctrlNS();

$action = "findAllCategories";
$categories = $ctrl->$action();
?>
<!-- add topic -->
<?php
if ($result['section'] === "topic") { ?>
    <div class="uk-grid-margin uk-grid uk-grid-stack" uk-grid>
        <div class="uk-width-1-2@m">
            <form id="newCAt" name="newCat" action="./index.php?ctrl=forum&action=addTopic" method="post" class="uk-form-horizontal uk-margin-large">
                <div class="uk-margin">
                    <select name="category" class="uk-select" aria-label="Select">
                        <?php foreach ($categories as $category) { ?>
                            <option value="<?= $category->getId() ?>"><?= $category->getName() ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="uk-margin">
                    <label class="uk-form-label" for="form-horizontal-text">Titre du Topic</label>
                    <div class="uk-form-controls">
                        <input name="title" class="uk-input" id="form-horizontal-text" type="text" placeholder="Nouveau topic">
                    </div>
                </div>
                <div class="uk-margin">
                    <textarea name="content" class="post">Premier Article obligatoire</textarea>
                </div>
                <input name="token-hidden" class="uk-input uk-form-large" type="text" value="<?= $_SESSION["token"] ?>" style="visibility:hidden">
                <button type="submit" class="uk-text-center color-primary uk-button uk-button-default uk-button-large uk-width-1-1">Ajouter Topic</button>
            </form>
        </div>
    </div>
<?php }
?>
<!-- add category -->
<?php
if ($result['section'] === "category") { ?>
    <div class="uk-grid-margin uk-grid uk-grid-stack" uk-grid>
        <div class="uk-width-1-2@m">
            <form id="newCAt" name="newCat" action="./index.php?ctrl=forum&action=addCategory" method="post" class="uk-form-horizontal uk-margin-large">
                <div class="uk-margin">
                    <label class="uk-form-label" for="form-horizontal-text">Ajouter une nouvelle catégorie</label>
                    <div class="uk-form-controls">
                        <input name="name" class="uk-input" id="form-horizontal-text" type="text" placeholder="Nom de Catégorie">
                    </div>
                </div>
                <input name="token-hidden" class="uk-input uk-form-large" type="text" value="<?= $_SESSION["token"] ?>" style="visibility:hidden">
                <button type="submit" class="uk-text-center color-primary uk-button uk-button-default uk-button-large uk-width-1-1">Ajouter Catégorie</button>
            </form>
        </div>
    </div>
<?php }
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