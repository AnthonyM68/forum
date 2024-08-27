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
$last5Topics = false; //$topics["data"]['topics'];
/**
 * on recherche les 5 derniers post publiés (col-right);
 */
$action = "listLast5Posts";
$posts = $ctrl->$action();
$posts = false; //$posts["data"]['posts'];

if (isset($result['section']) && $result['section'] === "home") {
?>
    <!-- CONTAINER ACCORDION -->
    <div id="news" class="uk-container">
        <hr class="uk-divider-icon">
        <div class="uk-grid-match uk-child-width-expand@m" uk-grid>
            <div class="uk-animation-fade">
                <div class="uk-card uk-card-default uk-card-body accordion-card">
                    <h3 class="uk-card-title color-primary uk-text-uppercase">5 derniers Topics</h3>
                    <ul uk-accordion>
                        <!-- 5 LAST TOPIC -->
                        <?php
                        if ($last5Topics) {
                            foreach ($last5Topics as $topic) { ?>
                                <li class="uk-closed">
                                    <a class="uk-accordion-title" href><?= htmlspecialchars_decode($topic->getTitle()) ?></a>
                                    <div class="uk-accordion-content">
                                        <!-- LIST TOPIC -->
                                        <ul> <?php // on recherche les 5 derniers posts
                                                $last5Posts = $ctrl->findLast5PostsByTopic($topic->getId());

                                                foreach ($last5Posts as $post) { ?>
                                                <li>
                                                    <p class="color-secondary">
                                                        <a href="index.php?ctrl=forum&action=showFullTopic&id=<?= $topic->getId() ?>&anchor=card-<?= $post->getId() ?>" class="color-link-topic-news"><?= htmlspecialchars_decode($post->getContent()) ?></a>
                                                    </p>
                                                    <span class="color-primary"> Crée le: <?= $post->getDateCreation() ?></span>
                                                </li>
                                            <?php }
                                            ?>
                                        </ul>
                                        <!-- ACCORDION FOOTER -->
                                        <p class="uk-accordion-footer">
                                            <span class="fas fa-user"></span>
                                            <!-- TOPIC AUTHOR -->
                                            <?php
                                            echo $topic->getUser()->getUsername() . " <small class='color-primary'>Auteur de ce topic: </small>";
                                            $roles = $topic->getUser()->getRole(); ?>

                                            <small class="color-primary">
                                                <?= $ctrl->convertToString($post->getTopic()->getUser()->getRole()) . " Depuis le: " . $topic->getDateCreation() ?>
                                            </small>
                                        </p>
                                        <!-- LINKS -->
                                        <span class="fa fa-reply" aria-hidden="true"></span>
                                        <a href="index.php?ctrl=forum&action=showFullTopic&id=<?= $topic->getId() ?>&anchor=post" class="color-primary">Répondre à la suite du topic</a>
                                    </div>
                                </li>
                        <?php }
                        }
                        ?>
                    </ul>
                </div>
            </div>
            <div class="uk-animation-fade">
                <div class="uk-card uk-card-default uk-card-body accordion-card">
                    <h3 class="uk-card-title color-primary uk-text-uppercase">5 derniers Posts</h3>
                    <!-- 5 LAST POSTS -->
                    <ul uk-accordion>
                        <?php
                        if ($posts) {
                            foreach ($posts as $post) { ?>
                                <li class="uk-open">
                                    <a class="" href="index.php?ctrl=forum&action=showFullTopic&id=<?= $post->getTopic()->getId() ?>"><?= htmlspecialchars_decode($post->getContent()) ?></a>
                                </li>
                        <?php }
                        } ?>
                    </ul>
                </div>
            </div>
        </div>
        <hr class="uk-divider-icon">
    </div>
<?php }
?>