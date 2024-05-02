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
    <div class="uk-padding uk-column-1-1">
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
            <input type="submit" class="uk-button uk-button-primary uk-button-large uk-width-1-1">
        </form>
    </div>
<?php }
?>
<!-- add category -->
<?php
if ($result['section'] === "category") { ?>
    <div class="uk-padding uk-column-1-2">
        <form id="newCAt" name="newCat" action="./index.php?ctrl=forum&action=addCategory" method="post" class="uk-form-horizontal uk-margin-large">
            <div class="uk-margin">
                <label class="uk-form-label" for="form-horizontal-text">Ajouter une nouvelle catégorie</label>
                <div class="uk-form-controls">
                    <input name="name" class="uk-input" id="form-horizontal-text" type="text" placeholder="Nom de Catégorie">
                </div>
            </div>
            <input type="submit" class="uk-button uk-button-primary uk-button-large uk-width-1-1">
        </form>
    </div>
<?php }
?>