$(document).ready(function(){
    $("#conference-creation-form").validate();
    $("#single-creation-form").validate();
    $("#collection-creation-form").validate();
    $(".date-picker-field").datepicker({
        dateFormat: "yy-mm-dd"
    });
});