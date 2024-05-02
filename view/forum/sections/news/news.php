<?php
// le nom de controleur à utilisé
$ctrlname = "forum";
//on construit le namespace de la classe Controller à appeller
$ctrlNS = "controller\\" . ucfirst($ctrlname) . "Controller";
$ctrl = new $ctrlNS();
/**
 * on recherche les 5 derniers topic publiés (col-left)
 */
$action = "listLast5Topics";
$topics = $ctrl->$action();
$topicsLenght5 = $topics["data"]['topics'];
/**
 * on recherche les 5 derniers post publiés (col-right);
 */
$action = "listLast5Posts";
$posts = $ctrl->$action();
$posts = $posts["data"]['posts'];

if (isset($result['section']) && $result['section'] === "home") {
    ?>
    <!-- CONTAINER ACCORDION -->
    <div id="news" class="uk-container">
        <div class="uk-grid-match uk-child-width-expand@m" uk-grid>
            <div class="uk-animation-fade">
                <div class="uk-card uk-card-default uk-card-body accordion-card">
                    <ul uk-accordion>
                        <!-- 5 LAST TOPIC -->
                        <?php
                        if ($topicsLenght5) {
                            foreach ($topicsLenght5 as $topic) { ?>
                                <li class="uk-closed">
                                    <a class="uk-accordion-title color-primary" href><?= $topic->getTitle() ?></a>
                                    <div class="uk-accordion-content">
                                        <!-- LIST TOPIC -->
                                        <ul>
                                            <?php // on recherche avec une LIMIT 5 post parmis ce topic
                                            $postsLenght = $ctrl->findAllPostByIdTopicLIMIT($topic->getId());
                                            if ($postsLenght) {
                                            foreach ($postsLenght as $post) { ?>
                                                <li>
                                                    <span class="color-primary"><?= $post->getContent() ?></span>
                                                    <span class="color-primary"> Crée le: <?= $post->getDateCreation() ?></span>
                                                </li>
                                            <?php }
                                            } ?>
                                        </ul>
                                        <!-- ACCORDION FOOTER -->
                                        <p class="uk-accordion-footer">
                                            <span class="fas fa-user"></span>
                                            <!-- TOPIC AUTHOR -->
                                            <?php
                                            echo $topic->getUser()->getUsername() . " ";
                                            $roles = $topic->getUser()->getRoles(); ?>
                                            <small
                                                class="color-primary"><?= $ctrl->convertToString($post->getTopic()->getUser()->getRoles()) . " Depuis le: " . $topic->getDateCreation() ?></span></small>
                                        </p>
                                        <!-- LINKS -->
                                        <span class="fa fa-reply" aria-hidden="true"></span>
                                        <a href="./index.php?ctrl=forum&action=addPost&id=<?= $topic->getId() ?>">Répondre à la
                                            suite du topic</a>
                                        <!--<span class="fa fa-edit" aria-hidden="true"></span>
                                        <a href="./index.php?ctrl=forum&action=listPostByIdTopic&id=<?= $topic->getId() ?>">Rejoindre le topic</a>-->
                                    </div>

                                </li>
                            <?php }
                        } ?>
                    </ul>
                </div>
            </div>
            <div class="uk-animation-fade">
                <div class="uk-card uk-card-default uk-card-body accordion-card">
                    <ul uk-accordion>
                        <!--<?php for ($i = 0; $i < 5; $i++) { ?>
                        <li class="">
                            <a class="uk-accordion-title" href><?= $fakerFr->sentence ?></a>
                        </li>
                    <?php } ?>-->
                        <?php
                        if ($posts) {
                            foreach ($posts as $post) { ?>
                                <li class="uk-open">
                                    <a class="uk-accordion-title" href><?= $post->getContent() ?></a>
                                </li>
                            <?php }
                        } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
<?php }
?>