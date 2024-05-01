<div class="uk-animation-fade uk-section">
    <div class="uk-container">
        <nav aria-label="Breadcrumb">
            <ul class="uk-breadcrumb pridi-regular">
                <li><a href="./">Accueil</a></li>
                <li><a href="#">lien 1</a></li>
                <li><a href="#">Lien 2</a></li>
                <li><span aria-current="page">Lorem ipsum dolor sit amet.</span></li>
            </ul>
        </nav>
        <div class="uk-grid-match uk-child-width-expand@m" uk-grid>
            <div class="pridi-regular">
                <div class="uk-card uk-card-default uk-card-body">
                    <p>&copy; <?= date_create("now")->format("Y") ?> -
                        <a href="#">Règlement du forum</a> -
                        <a href="#">Mentions légales</a>
                    </p>
                </div>
            </div>
            <div>
                <div class="uk-card uk-card-default uk-card-body">
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
                </div>
            </div>
        </div>
        <p class="uk-text-center">&copy; <?= date_create("now")->format("Y") ?></p>
    </div>
</div>