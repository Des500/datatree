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