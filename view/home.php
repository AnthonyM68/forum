<?php 
$categoryDatas = $result['data']['categoryData'];



?>
<!-- TITLE PAGE -->
<h1 class="uk-padding-small uk-padding-remove-horizontal pridi-regular uk-animation-slide-bottom">Bienvenue sur le Forum
</h1>

<div class="uk-section uk-padding-remove">
    <div class=" uk-container">
        <div class="" uk-grid>
            <div class="uk-width-expand">
                <!-- CARD -->
                <?php
                if ($categoryDatas) {
                    foreach ($categoryDatas as $category) { ?>
                        <div class="uk-grid-small uk-flex-middle" uk-grid>
                            <h3><a href="index.php?ctrl=forum&action=listTopicsByCategory&id=<?= $category->getId() ?>"><?= $category->getName() ?></a></h3>
                            <!--<span class="uk-label uk-margin-auto-left color-secondary">Rejoindre</span>-->
                            <span></span>
                        </div>
                <?php }
                }
                ?>
            </div>
            <div class="uk-width-1-4@m uk-padding-small">
               <?php 
               if(isset($result['data']['lastUser']) && $result['data']['lastUser'] !== false) {
                ?>
                <div class="uk-card uk-card-default accordion-card uk-width-1-1@m uk-box-shadow-large">
                    <div class="uk-card-header">
                        <h3 class="uk-card-title pridi-medium">Dernier inscrit</h3>
                        <!-- PROFIL LAST USER-->
                        <div class="uk-grid-small uk-flex-middle" uk-grid>
                            <!-- IMAGE -->
                            <div class="uk-width-auto">
                                <img class="uk-border-circle" width="40" height="40" src="./public/img/profils/moi.jpg" alt="Avatar">
                            </div>
                            <!-- USERNAME -->
                            <div class="uk-width-expand">
                                <h3 class="uk-card-title uk-margin-remove-bottom  pridi-regular">
                                    <?= $result['data']['lastUser'] ?>
                                </h3>
                                <p class="uk-text-meta uk-margin-remove-top"><time datetime="2016-04-01T19:00"><?= $lastUser['dateRegister'] ?></time></p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
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