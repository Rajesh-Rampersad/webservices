$(document).ready(function () {
    //$('#myModalWarning').modal('show');
   //console.log('Estoy listo'); 
   $("#formLogin").submit(function(){

        var usuario = $("#usuario").val().toLowerCase();
        var contrasena = $("#contrasena").val();
        //console.log(usuario+' - '+contrasena);

        $.ajax({
            async: true,
            type: "POST",
            url: "util/login/login.php",
            data:{
                ususario: usuario,
                contrasena: contrasena
            },
            datatype: 'json',
            beforeSend: function(){
                $('#btn_submit').html("Consultando");
                $('#btn_submit').prop("disabled",true);

            },
            error: function(request, status,error){
                alert(request.responseText);
            },
            success: function(respuesta){
               switch (respuesta.estado) {
                case 1:
                    document.location = '';
                    break;

                case 2:
                    $('#myModalWarningBody').html(respuesta.estado);
                    $('#myModalWarning').modal('show');
                    $("#contrasena").val('');
                    break;  
               
                default:
                    alert("Se ha producido un error");
                    break;
               }
            },
            complete: function(){
                $('#btn_submit').prop("disabled",false);
                $('#btn_submit').html("Acceder");
            }            

        });

        return false;
   });

});