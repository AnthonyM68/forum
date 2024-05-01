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
$posts = $posts["data"]['posts']; ?>

<div id="news" class="uk-container">
    <div class="uk-grid-match uk-child-width-expand@m" uk-grid>
        <div class="uk-animation-fade">

            <div class="uk-card uk-card-default uk-card-body">
                <ul uk-accordion>
                    <?php
                    if ($topicsLenght5) {
                        foreach ($topicsLenght5 as $topic) { ?>
                            <li class="uk-closed">
                                <a class="uk-accordion-title" href><?= $topic->getTitle() ?></a>
                                <div class="uk-accordion-content">
                                    <p>
                                    <ul>
                                        <?php // on recherche avec une LIMIT 5 post parmis ce topic
                                        $postsLenght = $ctrl->findAllPostByIdTopicLIMIT($topic->getId());
                                        if ($postsLenght) {
                                            foreach ($postsLenght as $post) { ?>
                                            <li><span class="get-content-post"><?= $post->getContent() ?></span><span class="get-date-creation"> Crée le: <?= $post->getDateCreation() ?></span></li>
                                        <?php }
                                        }
                                        ?>
                                    </ul>
                                    <span class="fas fa-user"></span>
                                    <?= $topic->getUser()->getUsername() . " " ?>
                                    <?php
                                        $roles = $topic->getUser()->getRoles();
                                        // Vérifier si $roles est un tableau ou une chaîne de caractères
                                        if (is_array($roles)) {
                                            $formattedRoles = "";
                                            foreach ($roles as $userRoles) {
                                                $formattedRoles .= ($userRoles === "ROLE_USER" ? "Membre du Forum" : ($userRoles === "ROLE_ADMIN" ? "Administrateur" : ""));
                                                $formattedRoles .= ", ";
                                            }
                                            $formattedRoles = rtrim($formattedRoles, ", ");
                                            echo "<small>$formattedRoles</small>";
                                        }
                                        echo $topic->getDateCreation(); ?>
                                    </p>
                                    <a href="./index.php?ctrl=forum&action=addPost&id=<?= $topic->getId() ?>">Répondre à la suite du topic</a>
                                </div>

                            </li>
                    <?php }
                    } ?>
                </ul>
            </div>
        </div>
        <div class="uk-animation-fade">
            <div class="uk-card uk-card-default uk-card-body">
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