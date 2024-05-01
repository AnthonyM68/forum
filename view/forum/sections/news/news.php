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
    <div id="news" class="uk-container">
        <div class="uk-grid-match uk-child-width-expand@m" uk-grid>
            <div class="uk-animation-fade">

                <div class="uk-card uk-card-default uk-card-body">
                    <ul uk-accordion>
                        <!-- Liste des 5 derniers topic -->
                        <?php
                        if ($topicsLenght5) {
                            foreach ($topicsLenght5 as $topic) { ?>
                                <li class="uk-closed">
                                    <a class="uk-accordion-title" href><?= $topic->getTitle() ?></a>
                                    <div class="uk-accordion-content">
                                        
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
                                        <p class="uk-accordion-footer">
                                            <span class="fas fa-user"></span>
                                            <?= $topic->getUser()->getUsername() . " " ?>
                                            <?php
                                            $roles = $topic->getUser()->getRoles();
                                            // Vérifier si $roles est un tableau ou une chaîne de caractères
                                            if (is_array($roles)) {
                                                // on retir le dernier élément du tableau
                                                $lastElement = array_pop($roles);
                                                // on initialise une string vide
                                                $formattedRoles = "";
                                                // s'il reste des rôles dans le tableau
                                                foreach ($roles as $userRoles) {
                                                    // on annalyse le contenu des rôles
                                                    $formattedRoles .= ($userRoles === "ROLE_USER" ? "Membre du Forum" : ($userRoles === "ROLE_ADMIN" ? "Administrateur" : ""));
                                                    // on ajoute la virgule
                                                    $formattedRoles .= ", ";
                                                }
                                                // si la chaine existe et non vide
                                                if ($formattedRoles !== "") {
                                                    // on retir la dernière virgule
                                                    $formattedRoles = rtrim($formattedRoles, ", ");
                                                    // on la remplace par un "et"
                                                    $formattedRoles .= " et";
                                                }
                                                // on ajoute un petit espace
                                                $formattedRoles .= " ";
                                                // on analyse le dernier élément du tableau de rôles initial
                                                $formattedRoles .= $lastElement === "ROLE_USER" ? "Membre du Forum" : ($lastElement === "ROLE_ADMIN" ? "Administrateur" : "");
                                            ?>
                                                <small class="get-roles-user">
                                                    <?= $formattedRoles ?>
                                                </small>
                                            <?php }
                                            echo $topic->getDateCreation(); ?>
                                        </p>
                                        <span class="fa fa-reply" aria-hidden="true"></span>
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
<?php }
?>