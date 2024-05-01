function limitCharFromString(parent, longueurMax) {
    $(`.${parent}`).each(function () {
        let texteComplet = $(this).text(); // Récupère le texte 
        let texteLimite = texteComplet.substring(0, longueurMax);
        $(this).text(`${texteLimite}...`); // Réaffiche le texte 
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

    $(".delete-btn").on("click", function () {
        return confirm("Etes-vous sûr de vouloir supprimer?")
    })
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
})