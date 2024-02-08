var carouselContainer = document.getElementById('carousel-container');
var frameWidth = 20; // Largeur d'un cadre en pourcentage de la largeur de l'écran
var maxFrames = 5;   // Nombre maximal de cadres à afficher
var accelerationFactor = 0.01; // Facteur d'accélération pour le défilement automatique
var lastCursorPosition = 0;
var framesQueue = [];
var isAutoScrolling = false;

// Fonction pour ajouter un nouveau cadre
function addFrame(position) {
    var newFrame = document.createElement('div');
    newFrame.className = 'floating-frame';
    newFrame.innerHTML = '<p>Nouveau Cadre</p>' +
                         '<a href="#" class="button">Bouton 1</a>' +
                         '<a href="#" class="button">Bouton 2</a>';
    newFrame.style.top = '50%';
    newFrame.style.left = position + '%';
    carouselContainer.appendChild(newFrame);
    framesQueue.push(newFrame);

    // Limiter le nombre total de cadres affichés
    if (framesQueue.length > maxFrames) {
        var removedFrame = framesQueue.shift();
        removedFrame.remove();
    }
}

// Fonction pour ajuster la position des cadres lors du défilement automatique
function adjustFramesPosition(offset) {
    var frames = carouselContainer.querySelectorAll('.floating-frame');
    frames.forEach(function (frame) {
        var framePosition = parseFloat(frame.style.left);
        frame.style.left = (framePosition + offset) + '%';
    });
}

// Fonction pour déclencher le défilement automatique
function startAutoScroll(direction) {
    isAutoScrolling = true;

    function autoScroll() {
        if (isAutoScrolling) {
            var cursorPosition = lastCursorPosition + accelerationFactor * direction;
            carouselContainer.style.transform = 'translateX(' + (cursorPosition * -100) + 'vw)';
            lastCursorPosition = cursorPosition;

            // Supprimer les cadres sortants
            var frames = carouselContainer.querySelectorAll('.floating-frame');
            frames.forEach(function (frame) {
                var framePosition = parseFloat(frame.style.left);
                if (framePosition < cursorPosition - 1 || framePosition > cursorPosition + 1) {
                    frame.remove();
                }
            });

            // Ajuster la position des cadres existants
            adjustFramesPosition((cursorPosition - 0.5) * 100);

            requestAnimationFrame(autoScroll);
        }
    }

    autoScroll();
}

// Fonction pour arrêter le défilement automatique
function stopAutoScroll() {
    isAutoScrolling = false;
}

// Ajouter un écouteur pour détecter le mouvement de la souris
document.addEventListener('mousemove', function (event) {
    // Calculer la position du curseur par rapport à la largeur de l'écran
    var cursorPosition = event.clientX / screen.width;

    // Vérifier si le mouvement du curseur est assez rapide pour déclencher le défilement automatique
    if (Math.abs(cursorPosition - lastCursorPosition) > accelerationFactor) {
        var direction = cursorPosition > lastCursorPosition ? 1 : -1;
        stopAutoScroll();
        startAutoScroll(direction);
    } else {
        stopAutoScroll();
    }

    lastCursorPosition = cursorPosition;
});

// Ajouter un écouteur pour détecter l'arrêt du mouvement de la souris
document.addEventListener('mouseup', function () {
    stopAutoScroll();
});
