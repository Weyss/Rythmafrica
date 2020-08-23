'use strict';

document.addEventListener('DOMContentLoaded', function () {
    // Script pour le menu hamburger en version mobile
    document.querySelector('#hamburger').addEventListener('click', function () {
        let nav = document.querySelector('nav');
        let ul = nav.querySelector('ul');
        if (ul.className == 'animationIn') {
            ul.classList.replace('animationIn', 'animationOut');
            nav.setAttribute('style', '');
            ul.addEventListener('animationend', function () {
                this.classList.remove('animationOut');
            })
        }
        else {
            ul.classList.add('animationIn');
            nav.setAttribute('style', 'z-index:100');
        }

    })

    // Script pour le carrousel
    let swiper = new Swiper('.swiper-container', {
        spaceBetween: 30,
        centeredSlides: true,
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
    });
})