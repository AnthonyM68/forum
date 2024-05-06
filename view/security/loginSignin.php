<div id="loginSignin" class="uk-flex-top" uk-modal>
    <div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical">
        <div class="uk-section uk-section-muted uk-flex uk-flex-middle uk-animation-fade" uk-height-viewport>
            <div class="uk-width-1-1">
                <div class="uk-container">
                    <div class="uk-grid-margin uk-grid uk-grid-stack" uk-grid>
                        <div class="uk-width-1-1@m">

                            <div class="uk-margin uk-width-large uk-margin-auto uk-card uk-card-default uk-card-body uk-box-shadow-large">

                                <ul class="uk-tab uk-flex-center" uk-grid uk-switcher="animation: uk-animation-fade">
                                    <li><a href="#">Connexion</a></li>
                                    <li><a href="#">Enregistrement</a></li>
                                    <li><a href="#">MDP</a></li>
                                    <li class="uk-hidden">Mot de passe perdu</li>
                                </ul>
                                <!-- login -->
                                <ul class="uk-switcher uk-margin">
                                    <li>
                                        <h3 class="uk-card-title uk-text-center">Connectez-vous</h3>
                                        <form id="login" name="login" action="./index.php?ctrl=security&action=login" method="post" required>
                                            <div class="uk-margin">
                                                <div class="uk-inline uk-width-1-1">
                                                    <span class="uk-form-icon" uk-icon="icon: mail"></span>
                                                    <input name="email" class="uk-input uk-form-large" type="text" placeholder="Email address" required>
                                                </div>
                                            </div>
                                            <div class="uk-margin">
                                                <div class="uk-inline uk-width-1-1">
                                                    <span class="uk-form-icon" uk-icon="icon: lock"></span>
                                                    <input name="password" class="uk-input uk-form-large" type="password" placeholder="Password" autocomplete="cc-csc" required>
                                                </div>
                                            </div>
                                            <div class="uk-margin uk-text-right@s uk-text-center uk-text-small">
                                                <a href="#" uk-switcher-item="2">Mot de pass perdu?</a>
                                            </div>
                                            <div class="uk-margin">
                                            <input name="token-hidden" class="uk-input uk-form-large" type="text" value="<?= $_SESSION["token"] ?>" style="visibility:hidden">
                                                <input type="submit" class="uk-button uk-button-primary uk-button-large uk-width-1-1">
                                            </div>
                                            <div class="uk-text-small uk-text-center">
                                                Non enregistré? <a href="#" uk-switcher-item="1">Créer un compte</a>
                                            </div>
                                        </form>
                                    </li>
                                    <!-- register -->
                                    <li>
                                        <h3 class="uk-card-title uk-text-center">Inscrivez-vous. C'est gratuit!</h3>
                                        <form id="register" name="register" action="./index.php?ctrl=security&action=register" method="post">
                                            <div class="uk-margin">
                                                <div class="uk-inline uk-width-1-1">
                                                    <span class="uk-form-icon" uk-icon="icon: user"></span>
                                                    <input name="username" class="uk-input uk-form-large" type="text" placeholder="Nom d'utilisateur" >
                                                </div>
                                            </div>
                                            <div class="uk-margin">
                                                <div class="uk-inline uk-width-1-1">
                                                    <span class="uk-form-icon" uk-icon="icon: mail"></span>
                                                    <input name="email" class="uk-input uk-form-large" type="text" placeholder="Adresse Email" >
                                                </div>
                                            </div>
                                            <div class="uk-margin">
                                                <div class="uk-inline uk-width-1-1">
                                                    <span class="uk-form-icon" uk-icon="icon: lock"></span>
                                                    <input name="password" class="uk-input uk-form-large" type="password" placeholder="Choisir mot de passe" autocomplete="cc-csc" >
                                                </div>
                                            </div>
                                            <div class="uk-margin">
                                                <div class="uk-inline uk-width-1-1">
                                                    <span class="uk-form-icon" uk-icon="icon: lock"></span>
                                                    <input name="repeat_password" class="uk-input uk-form-large" type="password" placeholder="Confirmez" autocomplete="cc-csc" >
                                                </div>
                                            </div>
                                            <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">
                                           
                                                <input name="rgpd" class="uk-checkbox" type="checkbox" required>J'accèpte les conditions d'utilisation.
                                                
                                            </div>
                                            <div class="uk-margin">
                                                <input name="token-hidden" class="uk-input uk-form-large" type="text" value="<?= $_SESSION["token"] ?>" style="visibility:hidden">
                                                <input type="submit" class="uk-button uk-button-primary uk-button-large uk-width-1-1" value="Créer compte">
                                            </div>
                                            <div class="uk-text-small uk-text-center">
                                                Vous avez déjà un compte? <a href="#" uk-switcher-item="0">Connexion</a>
                                            </div>
                                        </form>
                                    </li>
                         
                                    <li>
                                        <h3 class="uk-card-title uk-text-center">Mot de passe perdu?</h3>
                                        <p class="uk-text-center uk-width-medium@s uk-margin-auto">Entrez votre adresse e-mail et nous vous enverrons un lien pour réinitialiser votre mot de passe.</p>
                                        <form id="forget" name="forget" action="./index.php?ctrl=security&action=forget" method="post">
                                            <div class="uk-margin">
                                                <div class="uk-inline uk-width-1-1">
                                                    <span class="uk-form-icon" uk-icon="icon: mail"></span>
                                                    <input class="uk-input uk-form-large" type="text" placeholder="Adresse Email" required>
                                                </div>
                                            </div>
                                            <div class="uk-margin">
                                            <input name="token-hidden" class="uk-input uk-form-large" type="text" value="<?= $_SESSION["token"] ?>" style="visibility:hidden">
                                                <button class="uk-button uk-button-primary uk-button-large uk-width-1-1">Envoyer Email</button>
                                            </div>
                                            <div class="uk-text-small uk-text-center">
                                                <a href="#" uk-switcher-item="0">Retour connexion</a>
                                            </div>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>