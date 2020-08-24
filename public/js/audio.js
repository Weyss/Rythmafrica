'use strict';


document.addEventListener('DOMContentLoaded', function () {
    let articles = document.querySelectorAll('article');

    articles.forEach(function (article) {
        let playAudio = article.querySelector('.player-audio')
        let picture = article.querySelector('.js-picture');
        let play = article.querySelector('.js-play');
        let audio = article.querySelector('.js-audio');


        playAudio.addEventListener('mouseenter', function () {
            picture.animate([
                // keyframes
                { opacity: 1 },
                { opacity: 0.5 }
            ], {
                // timing options
                duration: 1000,
            });
            picture.setAttribute('style', 'opacity:0.5');

            play.animate([
                // keyframes
                { opacity: 0 },
                { opacity: 1 }
            ], {
                // timing options
                duration: 1000,
            });
            play.setAttribute('style', 'opacity:1')
        })
        playAudio.addEventListener('mouseleave', function () {
            picture.animate([
                // keyframes
                { opacity: 0.5 },
                { opacity: 1 }
            ], {
                // timing options
                duration: 1000,
            });
            picture.setAttribute('style', '');

            play.animate([
                // keyframes
                { opacity: 1 },
                { opacity: 0 }
            ], {
                // timing options
                duration: 500,
            });
            play.setAttribute('style', '')
        })







        play.addEventListener('click', function () {
            audio.play();
        })
    })
})