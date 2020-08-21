'use strict';

// Pour la barre de recherche afin qu'elle passe au dessus du carousel
function opacity() {
    if (screen.width > 767) {
        if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
            let search = document.querySelector('#searchBar');
            search.setAttribute('style', 'z-index:1000');
        }
    } else if (document.body.scrollTop < 200 || document.documentElement.scrollTop < 200) {
        let search = document.querySelector('#searchBar');
        search.setAttribute('style', '');
    }
}




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

})