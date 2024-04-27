
$(document).ready(function () {

    let modal = UIkit.modal("#loginSignin");
    modal.show();

    
    // on modifie la taille des titre des accordéons
    $('.uk-accordion-title').each(function () {
        var texteComplet = $(this).text(); // Récupère le texte 
        var longueurMax = 30; // Limite de la longueur
        var texteLimite = texteComplet.substring(0, longueurMax);
        $(this).text(`${texteLimite}...`); // Réaffiche le texte 
    });


    // Sélectionnez la colonne de gauche
    var colLeft = document.querySelector('.uk-resize-vertical');
    // Sélectionnez la section parente
    var section = document.querySelector('.uk-section');
    // Sélectionnez l'élément parallaxe
    var parallaxElement = document.querySelector('#test-start-end .uk-card');

    // Si les éléments existent
    if (colLeft && section && parallaxElement) {
        // Obtenez la hauteur de la colonne de gauche
        var colLeftHeight = colLeft.offsetHeight;
        
        // Définir la hauteur de la section parente
        section.style.height = colLeftHeight + 'px';
        // Définir la position de départ du parallaxe en fonction de la hauteur de la colonne de gauche
        parallaxElement.setAttribute('uk-parallax', 'target: #test-start-end; start: -100; end: 0; y: 398; easing: 0;');
    }




    /* Elan */
    $(".message").each(function () {
        if ($(this).text().length > 0) {
            $(this).slideDown(500, function () {
                $(this).delay(3000).slideUp(500)
            })
        }
    })
    $(".delete-btn").on("click", function () {
        return confirm("Etes-vous sûr de vouloir supprimer?")
    })
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