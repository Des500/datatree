document.addEventListener('DOMContentLoaded', function(){

    // Проверка полей формы элемента
    function checkTreeForm () {
        let valid = true;
        if (document.SendForm.title.value.length < 3) {
            alert('Название должно быть больше 3 символов');
            valid =false;
        }
        if (document.SendForm.description.value.length < 10) {
            alert('Описание должно быть больше 10 символов');
            valid =false;
        }
        console.log(valid);
        return valid;
    }

    // сокрытие информационных билбордов
    document.querySelectorAll('#notif-message div').forEach(function (item) {
        setTimeout( function () {
            item.classList.add('form_close');
        }, 3000);
        setTimeout( function () {
            item.className = 'form_closed';
            item.innerHTML = '';
        }, 4000);
    });

    // получение и вывод информации об элементе ajax запрос
    function getElement (itemId) {
        let request = new XMLHttpRequest();
        let url = '/tree/getElementAjax/' + itemId;
        request.open("POST", url, true);
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        request.addEventListener("readystatechange", () => {
            if (request.readyState === 4 && request.status === 200) {
                itemContent = JSON.parse(request.responseText);
                document.querySelector('#element-title').innerHTML = itemContent['id'] + '|' + itemContent['title'];
                document.querySelector('#element-desc').innerHTML = itemContent['description'];
                document.querySelector('#element-desc-null').innerHTML = '';

                document.querySelector('#element-edit').style.display = '';
                document.querySelector('#element-add').style.display = '';
                document.querySelector('#element-delete').style.display = '';

                document.querySelector('#element-edit').setAttribute('href', '/tree/edit/' + itemId);
                document.querySelector('#element-add').setAttribute('href', '/tree/add/' + itemId);
                document.querySelector('#element-delete').setAttribute('href', '/tree/delete/' + itemId);

            }
        });
        request.send();
        return true;
    }

    // получение и вывод информации об элементе - выбор ID
    document.querySelectorAll('.links a').forEach(function (item) {
        item.addEventListener( 'click', function () {
            itemId = item.id.split('-')[1];
            if (itemId == 0) {
                document.querySelector('#element-title').innerHTML = 'Выберите элемент';
                document.querySelector('#element-desc').innerHTML = '';
                document.querySelector('#element-desc-null').innerHTML = 'или добавьте по кнопке ниже';

                document.querySelector('#element-edit').style.display = 'none';
                document.querySelector('#element-delete').style.display = 'none';

                document.querySelector('#element-add').setAttribute('href', '/tree/add/0');
                return;
            }
            else {
                getElement(itemId);
            }
            if (window.innerWidth<=900)
                document.querySelector('#menu-checkbox').checked = false;
        })
    })

    // восстановление прошлой позиции после редактирования или добавления элемента
    docPath = document.location.pathname.split('/');
    if ((docPath[2] == 'adminpanel')&&(docPath[3]>0)) {
        getElement(docPath[3]);
    }
});