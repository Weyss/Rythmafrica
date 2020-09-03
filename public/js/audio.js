'use strict';

function playerAudio() {
    let articles = document.querySelectorAll('article');
    let actuallyPlayed = null;
    articles.forEach(function (article) {
        /** Variables **/
        let playAudio = article.querySelector('.player-audio')
        let picture = article.querySelector('.js-picture');
        let play = article.querySelector('.js-play');
        let audio = article.querySelector('.js-audio');
        let pause = article.querySelector('.js-pause');
        let controls = article.querySelector('.controls');
        let progress = article.querySelector('.progress');
        let timeline = article.querySelector('.timeline');
        let volume = article.querySelector('.custom-slider');
        let volumeUp = article.querySelector('.volumeUp');

        audio.volume = volume.value / 100;

        /** Fonction pour changer le volume **/
        volume.addEventListener('change', function () {
            audio.volume = volume.value / 100;
        });

        /** Fonction pour faire apparaitre des éléments lorsque la souris est sur l'image **/
        playAudio.addEventListener('mouseenter', function () {
            if (audio.paused == true) {
                if (audio.currentTime == 0) {
                    progress.animate([
                        // keyframes
                        { opacity: 0 },
                        { opacity: 1 }
                    ], {
                        // timing options
                        duration: 500,
                    });
                    progress.classList.add('js-visible');
                }

                picture.animate([
                    // keyframes
                    { opacity: 1 },
                    { opacity: 0.5 }
                ], {
                    // timing options
                    duration: 1000,
                });
                picture.setAttribute('style', 'opacity:0.5');
            }

            play.animate([
                // keyframes
                { opacity: 0.7 },
                { opacity: 1 }
            ], {
                // timing options
                duration: 1000,
            });
            play.setAttribute('style', 'opacity:1');

            volumeUp.animate([
                // keyframes
                { opacity: 0 },
                { opacity: 1 }
            ], {
                // timing options
                duration: 500,
            });
            volumeUp.classList.add('js-visible');

        })

        /** Fonction pour changer le volume **/
        volumeUp.addEventListener('click', function () {
            volume.classList.toggle('js-visible')
        })

        /** Fonction pour changer le volume **/
        playAudio.addEventListener('mouseleave', function () {
            play.animate([
                // keyframes
                { opacity: 1 },
                { opacity: 0.5 }
            ], {
                // timing options
                duration: 1000,
            });
            play.setAttribute('style', 'opacity:0.5');

            if (audio.paused == true) {
                picture.animate([
                    // keyframes
                    { opacity: 0.5 },
                    { opacity: 1 }
                ], {
                    // timing options
                    duration: 1000,
                });
                picture.setAttribute('style', '');
                if (audio.currentTime == 0) {
                    progress.animate([
                        // keyframes
                        { opacity: 1 },
                        { opacity: 0 }
                    ], {
                        // timing options
                        duration: 500,
                    });
                    progress.classList.remove('js-visible');
                }
            }
            volume.animate([
                // keyframes
                { opacity: 1 },
                { opacity: 0 }
            ], {
                // timing options
                duration: 500,
            });
            volume.classList.remove('js-visible');
            volumeUp.animate([
                // keyframes
                { opacity: 1 },
                { opacity: 0 }
            ], {
                // timing options
                duration: 500,
            });
            volumeUp.classList.remove('js-visible');
        })

        /** Fonction pour mettre en play / pause **/
        controls.addEventListener('click', function () {
            if (!audio.paused) {
                play.classList.remove('js-hidden');
                pause.classList.remove('js-visible');
                audio.pause();
            } else {
                if (actuallyPlayed != audio) {
                    // On chercher à savoir si une musique est déja en lecture
                    // Si c'est le cas on stop la musique en cours pour mettre en lecture
                    // la musique sur laquelle le clic vient de se produire
                    if (actuallyPlayed != null) {
                        actuallyPlayed.pause();
                        actuallyPlayed.currentTime = 0;
                        actuallyPlayed.parentNode.querySelector('.js-play').classList.remove('js-hidden');
                        actuallyPlayed.parentNode.querySelector('.js-pause').classList.remove('js-visible');
                    }
                    actuallyPlayed = audio;
                }
                play.classList.add('js-hidden');
                pause.classList.add('js-visible');
                audio.play();
            }
        })

        /** Fonction pour la barre de progression  **/
        audio.addEventListener('timeupdate', function () {
            let timelinePos = audio.currentTime / audio.duration;
            timeline.style.height = 100 + '%';
            timeline.style.width = timelinePos * 100 + '%';

            if (audio.ended) {
                play.classList.replace('js-hidden', 'js-visible');
                pause.classList.remove('js-visible');
            }
        })

        /** Fonction pour rendre la barre de progression cliquable **/
        progress.addEventListener('click', function (e) {
            let rect = progress.getBoundingClientRect();
            let width = rect.width;
            let x = e.clientX - rect.left;
            let widthPercent = ((x * 100) / width);
            let currentTimeTrue = (widthPercent * audio.duration) / 100;

            audio.currentTime = currentTimeTrue
        })
    })
}


document.addEventListener('DOMContentLoaded', function () {
    playerAudio()
});