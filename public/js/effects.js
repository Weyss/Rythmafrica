'use strict';

document.addEventListener('DOMContentLoaded', function () {
    // Script pour le menu hamburger en version mobile
    document.querySelector('#hamburger').addEventListener('click', function () {
        let ul = document.querySelector('ul');
        if (ul.className == 'animationIn') {
            ul.classList.replace('animationIn', 'animationOut');
            ul.addEventListener('animationend', function () {
                this.classList.remove('animationOut');
            })
        }
        else {
            ul.classList.add('animationIn');
        }

    })

    // Script pour le carrousel
    let swiper = new Swiper('.swiper-container', {
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
        breakpoints: {
            640: {
              slidesPerView: 2,
              spaceBetween: 20,
            },
            768: {
              slidesPerView: 3,
              spaceBetween: 40,
            },
            1024: {
              slidesPerView: 3,
              spaceBetween: 50,
            },
        }
    });
})