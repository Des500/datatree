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