// Проверка полей формы элемента
function checkTreeForm () {
    valid = true;
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
    let original_class = item.className;
    setTimeout( function () {
        item.className = original_class+' form_close';
    }, 5000);
    setTimeout( function () {
        item.className = 'form_closed';
        item.innerHTML = '';
    }, 6000);
});

// получение информации об элементе
document.querySelectorAll('.links a').forEach(function (item) {
    item.addEventListener( 'click', function () {
        itemId = item.id.split('-')[1];
        console.log(window.innerWidth);
        if (window.innerWidth<=900)
            document.location.href = '/tree/index/' + itemId;
        if (itemId == 0) {
            document.querySelector('#element-title').innerHTML = 'Выберите элемент';
            document.querySelector('#element-desc').innerHTML = '';
            return;
        }
        let request = new XMLHttpRequest();
        let url = '/tree/getElementAjax/' +itemId ;
        request.open("POST", url, true);
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        request.addEventListener("readystatechange", () => {
            if(request.readyState === 4 && request.status === 200) {
                itemContent = JSON.parse(request.responseText);
                document.querySelector('#element-title').innerHTML = itemContent['id'] + '|' + itemContent['title'];
                document.querySelector('#element-desc').innerHTML = itemContent['description'];
            }
        });
        request.send();
    })
})