function logoutSession(){
    document.location = 'util/system/logout.php';
}

$(document).ready(function (){
    var param_timeout = $("#param_timeout").val();

    setInterval(logoutSession, ( parseInt(param_timeout) * 60000 ));
});