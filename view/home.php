<?php
$categoryDatas = $result['data']['categoryData'];
?>
<h1 class="uk-padding-small uk-padding-remove-horizontal pridi-regular uk-animation-slide-bottom">Bienvenue sur le Forum
</h1>
<div class="uk-section uk-padding-remove">
    <div class=" uk-container">
        <div class="" uk-grid>
            <div class="uk-width-expand">
                <div class="uk-card uk-card-default uk-card-body accordion-card ">
                    <ul uk-accordion>
                        <?php
                        if ($categoryDatas) {
                            foreach ($categoryDatas as $categoryName => $categoryData) { ?>
                                <li class="uk-closed">
                                    <a class="uk-accordion-title color-primary" href><?= $categoryName ?></a>
                                    <div class="uk-accordion-content">
                                        <ul>
                                            <?php
                                            foreach ($categoryData as $topicData) {
                                                $topic = $topicData['topic'];
                                                $posts = $topicData['posts']; ?>

                                                <li><?= $topic->getTitle() ?></li>
                                                <?php foreach ($posts as $post) { ?>
                                                    <li><?= $post->getContent() ?></li>
                                            <?php }
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                </li>
                        <?php }
                        }
                        ?>
                    </ul>
                </div>
            </div>
            <div class="uk-width-1-4@m uk-padding-small">
                <div class="uk-card uk-card-default uk-width-1-1@m  uk-box-shadow-large">
                    <div class="uk-card-header">
                        <h3 class="uk-card-title pridi-medium">Dernier inscrit</h3>
                        <!-- PROFIL -->
                        <div class="uk-grid-small uk-flex-middle" uk-grid>
                            <!-- IMAGE -->
                            <div class="uk-width-auto">
                                <img class="uk-border-circle" width="40" height="40" src="./public/img/profils/moi.jpg" alt="Avatar">
                            </div>
                            <div class="uk-width-expand">
                                <h3 class="uk-card-title uk-margin-remove-bottom  pridi-regular"><?= $fakerFr->name ?>
                                </h3>
                                <p class="uk-text-meta uk-margin-remove-top"><time datetime="2016-04-01T19:00"><?= $fakerFr->date('d/m/Y') ?></time></p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- STATES -->
                <table class="uk-table uk-table-middle uk-table-divider">
                    <thead>
                        <tr class="pridi-medium">
                            <th class="uk-width-small">Statistiques</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="pridi-regular">
                            <td>Membres</td>
                            <td><?= $result['countUsers'] ?></td>
                        </tr>
                        <tr class="pridi-regular">
                            <td>Catégories</td>
                            <td><?= $result['countCategories'] ?></td>
                        </tr>
                        <tr class="pridi-regular">
                            <td>Sujets</td>
                            <td><?= $result['countTopics'] ?></td>
                        </tr>
                        <tr class="pridi-regular">
                            <td>Articles</td>
                            <td><?= $result['countPosts'] ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>