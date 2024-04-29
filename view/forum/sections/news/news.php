<?php
$ctrlname = "forum";
//on construit le namespace de la classe Controller à appeller
$ctrlNS = "controller\\" . ucfirst($ctrlname) . "Controller";
$ctrl = new $ctrlNS();

$action = "listLast5Topics";
$topics = $ctrl->$action();
$topics = $topics["data"]['topics'];
()
$action = "listLast5Posts";
$posts = $ctrl->$action();
//$category = $result["data"]['category'];
$posts = $posts["data"]['posts'];

var_dump()
?>

<div id="news" class="uk-container">
    <div class="uk-grid-match uk-child-width-expand@m" uk-grid>
        <div>
            <div class="uk-panel uk-light uk-margin-medium">
                
                <a href="index.php?ctrl=forum&action=addTopic"><h3>Nouveaux Topics</h3></a>
            </div>
            <div class="uk-card uk-card-default uk-card-body">
                <ul uk-accordion>
                    <!--<?php for ($i = 0; $i < 5; $i++) { ?>
                        <li class="">
                            <a class="uk-accordion-title" href><?= $fakerFr->sentence ?></a>
                            <a class="uk-accordion-title"><?= $topic?></a>
                        </li>
                    <?php }
                    ?>-->

                    <?php
                        foreach ($topics as $topic) { ?>
                        <li class="uk-closed">
                            <a class="uk-accordion-title" href><?= $topic->getTitle() ?></a>
                            <div class="uk-accordion-content"><a href="./index.php?ctrl=forum&action=addPost&id=<?= $topic->getId() ?>">Répondre</a></div>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
        <div>
            <div class="uk-panel uk-light uk-margin-medium">
                <a href="index.php?ctrl=forum&action=addPost"><h3>Nouveaux Messages</h3></a>
            </div>
            <div class="uk-card uk-card-default uk-card-body">
                <ul uk-accordion>
                    <!--<?php for ($i = 0; $i < 5; $i++) { ?>
                        <li class="">
                            <a class="uk-accordion-title" href><?= $fakerFr->sentence ?></a>
                        </li>
                    <?php } ?>-->
                    <!--<?php
                        foreach ($posts as $post) { ?>
                        <li class="uk-open">
                            <a class="uk-accordion-title" href><?= $post->getContent() ?></a>
                        </li>
                    <?php } ?>-->
                </ul>
            </div>
        </div>
    </div>
</div>