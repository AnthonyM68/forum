function displayFormConfirmDelete(id)
{
    return ` <div class="uk-animation-fade uk-container">
    <div class="uk-grid-margin uk-grid uk-grid-stack" uk-grid>
        <div class="uk-width-1-1@m">
            <h1 class="pridi-regular uk-animation-slide-top">Confirmez la suppresion par mot de passe</h1>
            <form id="delete-account" action="index.php?ctrl=security&action=deleteAccount&id=${id}" method="post" uk-grid>
                <legend class="uk-legend color-secondary uk-animation-slide-top"></legend>
                <div class="uk-width-1-2@s">
                    <div class="uk-grid-small" uk-grid>
                        <div class="uk-width-1-2@s">
                            <label class="uk-form-label" for="password">Nouveau mot de passe</label>
                            <div class="uk-form-controls">
                                <input name="password" class="uk-input uk-width-1-1" type="password" uk-autocomplete>
                            </div>
                        </div>
                        <div class="uk-width-1-2@s">
                            <label class="uk-form-label" for="repeat_password">Répéter mot de passe</label>
                            <div class="uk-form-controls">
                                <input name="repeat_password" class="uk-input uk-width-1-1" type="password" uk-autocomplete>
                            </div>
                        </div>
                    </div>
                    <button class="uk-margin uk-button uk-button-default">Soumettre</button>
                </div>
                <!-- second col -->
            </form>
        </div>
    </div>
    </div>`;
}
// faire defiler la page jusqu'à une ancre déclarer dans le controller
function scrollToAnchor(anchor) {
    let element = document.querySelector(anchor);
    if (element) {
        element.scrollIntoView();
    }
}
// on limite une chaine de caractère à une certaine taille
function limitCharFromString(parent, longueurMax) {
    $(`.${parent}`).each(function () {
        let texteComplet = $(this).text(); // Récupère le texte 
        let texteLimite = texteComplet.substring(0, longueurMax);
        $(this).text(`${texteLimite}...`); // Réaffiche le texte 
    });
}

/**
 * Cette boucle est utile pour déterminer la hiérarchie actuelle de la page en fonction de l'URL et des mappings définis dans urlMappings.
 * Elle permet de construire le fil d'Ariane en identifiant où se trouve actuellement l'utilisateur dans la structure de navigation de la page.
 * @param {*} currentUrl url où l'on se trouve
 * @param {*} urlMappings l'objet maps contenant les url à vérifier et les Label à utilisés
 * @returns 
 */
function getCurrentHierarchy(currentUrl, urlMappings) {
    let hierarchy = [];
    // on parcours l'objet js à la recherche de l'url (currentUrl)
    // parmis les clés de l'objet
    for (let url in urlMappings) {
        // la méthode startsWith de l'objet String permet de vérifier 
        // si une chaîne de caractères commence par une sous-chaîne donnée
        // ex: string.startsWith(searchString)
        if (currentUrl.startsWith(url)) {
            // Si c'est le cas une nouvelle entrée est ajoutée à la hiérarchie 
            // avec le libellé correspondant extrait du tableau urlMappings 
            hierarchy.push({
                label: urlMappings[url],
                url: url
            });
        }
    }
    // on retourne la hiérarchie
    return hierarchy;
}
/**
 * 
 * @param {*} currentUrl  L'URL actuelle de la page
 * @param {*} urlMappings Un objet contenant les correspondances entre les URL et les libellés des pages dans la hiérarchie du fil d'Ariane
 * @returns le fil d'ariane
 */
function generateBreadcrumb(currentUrl, urlMappings) {
    // breadcrumb stockera les éléments du fil d'Ariane sous forme de chaînes HTML.
    let breadcrumb = [];
    // pour obtenir la hiérarchie actuelle basée sur l'URL actuelle et les correspondances URL dans urlMappings
    let currentHierarchy = getCurrentHierarchy(currentUrl, urlMappings);
    // Un lien vers la page d'accueil est ajouté en premier dans le fil d'Ariane.
    breadcrumb.push('<a href="./">Accueil</a>');

    // Vérifier si "Breadcrumb" est présent dans la hiérarchie
    let breadcrumbFound = false;
    for (let i = 0; i < currentHierarchy.length; i++) {
        if (currentHierarchy[i].label === 'Breadcrumb') {
            breadcrumbFound = true;
            breadcrumb.push('<a href=".' + currentHierarchy[i].url + '">Breadcrumb</a>');
            break;
        }
    }

    // Ajouter chaque niveau de la hiérarchie au fil d'Ariane
    for (let i = 0; i < currentHierarchy.length; i++) {
        let item = currentHierarchy[i];
        // Exclure "Breadcrumb" de la deuxième itération
        if (breadcrumbFound && item.label === 'Breadcrumb') {
            continue;
        }
        breadcrumb.push('<a href=".' + item.url + '">' + item.label + '</a>');
    }

    return breadcrumb.join(' > '); // Utilisez ">" pour le symbole ">"
}

// IN WORKING 

// effectuer la suppression du compte 
function deleteAccount() {
    let element = document.querySelector("#delete-account-btn");
    let id = element.dataset.id;
    //alert(`index.php?ctrl=security&action=deleteAccount&id=${id}`);
    // Effectuer une requête AJAX 
    fetch(`index.php?ctrl=security&action=deleteAccount`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => {
            if (response.ok) {
                return response.json(); // Convertir la réponse JSON
            } else {
                throw new Error('Network response was not ok.');
            }
        })
        .then(data => {
            console.log(data.id); // Afficher la réponse JSON
            let page = document.getElementById('page');
            if(page) {
                page.innerHTML = displayFormConfirmDelete(data.id);
            }
        })
        .catch(error => {
            console.error('Erreur lors de la récupération de l\'id utilisateur:', error);
            alert("Une erreur s'est produite. Veuillez réessayer plus tard.");
        });
}

$(document).ready(function () {
    // on affiche le modal si dans la vue rendu il existe
    let modal = UIkit.modal("#loginSignin");
    if (modal) { modal.show(); }
    // on modifie la taille des titre des accordéons pour plus de style
    limitCharFromString("uk-accordion-title", 40);
    // on modifie la taille des contenus des accordéons pour plus de style
    limitCharFromString("get-content-post", 40)
    /* Elan masquage des alert() après 3sec */
    $(".message").each(function () {
        if ($(this).text().length > 0) {
            //$(this).addClass("alert-padding");
            $(this).slideDown(500, function () {
                $(this).delay(3000).slideUp(500, function () {
                    // un petit style a ajouter
                    //$(this).removeClass("alert-padding");
                });
            });
        }
    });
    // éditeur de text pour les utilisateurs
    tinymce.init({
        selector: '.post',
        menubar: false,
        plugins: [
            'advlist autolink lists link image charmap print preview anchor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table paste code help wordcount'
        ],
        toolbar: 'undo redo | formatselect | ' +
            'bold italic backcolor | alignleft aligncenter ' +
            'alignright alignjustify | bullist numlist outdent indent | ' +
            'removeformat | help',
        content_css: '//www.tiny.cloud/css/codepen.min.css'
    });
    // objet javascript pour le breadcrumb
    let urlMappings = {
        './': 'Accueil',
        '/index.php?ctrl=forum&action=index': 'Liste des catégories',
        '/index.php?ctrl=security&action=allUsers': 'Liste des membres',
        '/index.php?ctrl=forum&action=addPost&id=': 'Sujet',
        '/index.php?ctrl=forum&action=index': 'Topic',

    };
    // on recherche l'url
    let currentUrl = window.location.href;
    // on récupérer le chemin après "index.php"
    let pathAfterIndex = currentUrl.substr(currentUrl.indexOf('/index.php'));
    // on génére le fil d'Ariane
    let breadcrumb = generateBreadcrumb(pathAfterIndex, urlMappings);
    let navBreadcrumb = document.getElementById('nav-breadcrumb');
    // Insérer le fil d'Ariane généré dans le DOM
    if (navBreadcrumb) {
        navBreadcrumb.innerHTML = breadcrumb;
    }
    // suppresion du compte utilisateur par l'espace profil
    let deleteAcc = document.getElementById('delete-account-btn');
    // on place un écouteur d'événement su rle bouton de suppresion
    if (deleteAcc) {
        deleteAcc.addEventListener('click', function (event) {
            event.preventDefault();
            // Afficher une boîte de dialogue de confirmation
            if (confirm("Êtes-vous sûr de vouloir supprimer votre compte ?")) {
                // Si l'utilisateur confirme, effectuer la suppression du compte via une requête AJAX
                deleteAccount();
            }
        });
    }

})