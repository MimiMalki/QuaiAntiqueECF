// // Récupérez tous les éléments avec la classe CSS 'heure'
// const heures = document.querySelectorAll('.form-check');

// // Parcourez chaque élément
// heures.forEach(function(heure) {
//     // Ajoutez un écouteur d'événement de clic
//     heure.addEventListener('click', function() {
//         // Supprimez la classe CSS 'selected' de tous les éléments
//         heures.forEach(function(el) {
//             el.classList.remove('selected');
//         });
        
//         // Ajoutez la classe CSS 'selected' à l'élément cliqué
//         heure.classList.add('selected');
//     });
// });

// Récupérez tous les éléments avec la classe CSS 'heure'
const heures = document.querySelectorAll('.heure');

// Parcourez chaque élément
heures.forEach(function(heure) {
    // Ajoutez un écouteur d'événement de clic
    heure.addEventListener('click', function() {
        // Supprimez la classe CSS 'selected' de tous les éléments
        heures.forEach(function(el) {
            el.classList.remove('selected');
        });
        
        // Ajoutez la classe CSS 'selected' à l'élément parent de l'élément cliqué
        heure.parentElement.classList.add('selected');
    });
});


