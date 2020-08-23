'use strict';

document.addEventListener('DOMContentLoaded', function(){
    let pictures = document.querySelectorAll('.js-picture');
    pictures.forEach(function(picture){
        picture.addEventListener('mouseover', function() {
            let play = document.querySelector('.js-play');
            play.classList.add('visible');
        })
    })
})