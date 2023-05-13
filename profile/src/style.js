
function toggleEditProfile(){
    var editFieldStatus = document.querySelector("#editFieldsStatus").innerHTML;
    if (editFieldStatus == '0') {
        document.querySelector("#editFieldsStatus").innerHTML = '1';
        document.querySelector("#edit_fields").style.display = 'block';
        document.querySelector("#editButton").innerHTML = 'Stop Editing Details';
    }else{
        document.querySelector("#editFieldsStatus").innerHTML = '0';
        document.querySelector("#edit_fields").style.display = 'none';
        document.querySelector("#editButton").innerHTML = 'Edit Personal Details';
    }

}