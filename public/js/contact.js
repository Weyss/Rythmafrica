'use strict';
let modalContact = null;
const focusableSelectors = 'input[type=text], input[type=email], button[type=submit], textarea';
let focusables = [];

/* Fonction pour récupérer le lien du formulaire **/
function contact(){
    let contact = document.querySelector('#js-contact');

    contact.addEventListener('click', function (e) {
        e.preventDefault();
        let url = this.href
        insertForm(url);
    })
}

/* Fonction pour insérer le formulaire **/
function insertForm(url) {
    let httpRequest = new XMLHttpRequest;
    httpRequest.onreadystatechange = function () {
        if (httpRequest.readyState === httpRequest.DONE && httpRequest.status == 200) {
            openModal();
            let modalBody = modalContact.querySelector('.modal-body');
            modalBody.innerHTML = httpRequest.responseText;

            modalContact.querySelector('from').addEventListener('submit', submitData);
        }
    }
    httpRequest.open('POST', url, true);
    httpRequest.send();
}

/* Fonction pour soumettre le formulaire **/
function submitData(e) {
    e.preventDefault();
    let data = new FormData(e.target);
    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === xhr.DONE && xhr.status == 200) {
            closeModal;
        }
    }
    xhr.open('POST', url, true);
    xhr.send(data);
}

/* Fonction pour ouvrir la modal **/
function openModal(){
    modalContact = document.querySelector('.modal');
    modalContact.removeAttribute('aria-hidden');
    modalContact.setAttribute('aria-modal', 'true');
    modalContact.classList.add('appear');
    modalContact.addEventListener('click', closeModal);
    modalContact.querySelector('.js-modal-close').addEventListener('click', closeModal)
    modalContact.querySelector('.js-modal-stop').addEventListener('click', stopPropagation)

}

/* Fonction pour fermer le formulaire **/
function closeModal(){
    modalContact.setAttribute('aria-hidden', 'true');
    modalContact.removeAttribute('aria-modal');
    window.setTimeout(function(){
        modalContact.classList.remove('appear');
        modalContact = null; 
    }, 500);
    
    modalContact.removeEventListener('click', closeModal);
    modalContact.querySelector('.js-modal-close').removeEventListener('click', closeModal)
    modalContact.querySelector('.js-modal-stop').removeEventListener('click', stopPropagation)
    
}

function stopPropagation(e){
    e.stopPropagation();
}

function focusInModal(e){
    focusables = Array.from(modalContact.querySelectorAll(focusableSelectors));
    e.preventDefault();
    let index = focusables.findIndex(f => f === modalContact.querySelector(':focus'));
    if(e.shiftKey === true){
        index--;
    }else{
        index++;
    }
    if(index >= focusables.length){
        index = 0
    }
    focusables[index].focus();
}

window.addEventListener('keydown', function(e){
    if (e.key === "Escape" || e.key === "Esc"){
        closeModal(e);
    }
    if(e.key === "Tab" && modalContact !== null){
        focusInModal(e);
    }
})

document.addEventListener('DOMContentLoaded', contact);