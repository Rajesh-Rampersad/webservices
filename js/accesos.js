function cargarOptionUsuarios() {
    $.ajax({
        type: "POST",
        url: "util/accesos/optionUsuarios.php",
        //data: {},
        dataType: 'json',
        beforeSend: function () {
            $("#usuarios").empty();
        },
        error: function (request, status, error) {
            alert(request.responseText);
        },
        success: function (respuesta) {
            switch (respuesta.estado) {
                case 1:

                    //console.log(respuesta);
                    var opciones = respuesta.data;

                    if (opciones.lenght > 0) {
                        var opcSelect = '';
                        for (var f = 0; f < opciones.length; f++) {
                            opcSelect += '<option ';
                            opcSelect += 'data-nombre="' + opciones[f]['nombre'] + '"';
                            opcSelect += 'value ="' + opciones[f]['usuario'] + '" >';
                            opcSelect += opciones[f]['usuario'] + ' - ' + opciones[f]['nombre'];
                            opcSelect += '</option';

                        }
                        $("#usuarios").html(opcSelect);
                        $("#usuarios").trigger("chosen:updated");
                    }

                    break;
                case 2:
                    $('#myModalWarningBody').html(respuesta.mensaje);
                    $('#myModalWarning').modal('show');
                    break;
                default:
                    alert("Se ha producido un error");
                    break;
            }
        },
        //complete: function(){}
    });
}

function cargarMenu() {
    $.ajax({
        type: "POST",
        url: "util/accesos/queryMenu.php",
        //data: {},
        dataType: 'json',
        beforeSend: function () {
            $("#sys_menu").empty();
        },
        error: function (request, status, error) {
            alert(request.responseText);
        },
        success: function (respuesta) {
            switch (respuesta.estado) {
                case 1:

                    //console.log(respuesta);
                    var menu = respuesta.data;
                    var sys_menu = '<ul id="myMenuPermisos">';

                    for (var f = 0; f < menu.lenght; f++) {
                        sys_menu += '<li class="open">';
                        sys_menu += '<input type="checkbox" id= "menu_' + (f + 1) + '" value="' + menu[f]['idmenu'] + '" class="sys_menu">';
                        sys_menu += '<label for="menu_' + (f + 1) + '">' + menu[f]['nombre'] + '</label>';

                        sys_menu += '<ul>';
                        var submenu = menu[f]['submenu'];

                        for (var i = 0; i < submenu.length; i++) {
                            sys_menu += '<li>';
                            sys_menu += '<input type="checkbox" id= "menu_' + (f + 1) + '_' + (i + 1) + '" value="' + submenu[i]['idmenu'] + '" class="sys_menu">';
                            sys_menu += '<label for="menu_' + (f + 1) + '_' + (i + 1) + '">' + submenu[i]['nombre'] + '</label>';
                            sys_menu += '</li>';

                        }


                        sys_menu += '</ul>';


                        sys_menu += '</li>';

                    }
                    sys_menu += '</ul>';

                    $("#sys_menu").html(sys_menu);

                    break;
                case 2:
                    $('#myModalWarningBody').html(respuesta.mensaje);
                    $('#myModalWarning').modal('show');
                    break;
                default:
                    alert("Se ha producido un error");
                    break;
            }
        },
        complete: function () {
            $("#myMenuPermisos").treeview({
                animated: "normal",
                collapsed: true,
                unique: false,
                persist: "location"
            });
        }
    });

}

$(document).ready(function () {
    cargarMenu();
    cargarOptionUsuarios();
    $("#usuarios").chosen({ width: "100%" });
});