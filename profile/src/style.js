
function enableEditing(){
    document.querySelector("#edit_fields").style.display = 'block';
    document.querySelector("#editButton").style.display = 'none';
}

function cancelEditing() {

    document.querySelector("#edit_fields").style.display = 'none';
    document.querySelector("#editButton").style.display = 'block';
}