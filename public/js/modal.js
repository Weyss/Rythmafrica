'use strict'

// FONCTIONS //
function clickAddArtist() {
    let httpRequest = new XMLHttpRequest;
        httpRequest.onreadystatechange = function () {
            if (httpRequest.readyState === 4) {
                if (httpRequest.status == 200) {
                    $('#form').modal('show');

                    let form = document.querySelector('#form .modal-body');
                    let text = httpRequest.responseText;

                    form.innerHTML = text;

                    let btnSave = document.querySelector('#js-save');
                    let formToSubmit = document.querySelector('form');

                    btnSave.addEventListener('click', function (e) {

                        e.preventDefault();
                        let data = new FormData(formToSubmit);
                        let xhr = new XMLHttpRequest;
                        xhr.onreadystatechange = function () {
                            if (xhr.readyState === 4) {
                                if (xhr.status == 200) {
                                    $('#form').modal('hide');
                                    //recup json update data
                                }
                            }
                        }
                        xhr.open('POST', "{{ path('form') }}", true);
                        xhr.send(data);
                    })
                }
            }
        }
        httpRequest.open('POST', "{{ path('artist_add') }}", true);
        httpRequest.send();
}



document.addEventListener('DOMContentLoaded', function () {
    
    let btn = document.querySelector('button.btn');
    btn.addEventListener('click', clickAddArtist);
});
