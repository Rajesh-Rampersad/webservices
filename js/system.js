function logoutSession(){
    document.location = 'util/system/logout.php';
} 

// console.log('System JS');

$(document).ready(function (){
    var param_timeout = $("#param_timeout").val();

    setInterval(logoutSession, ( parseInt(param_timeout) * 3600 ));
});