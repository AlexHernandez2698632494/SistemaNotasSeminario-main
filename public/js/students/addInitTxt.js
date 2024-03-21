//Este archivo inicializa los campos de la vista de agregar estudiante(seminarista)

document.addEventListener("DOMContentLoaded", function(event) {    
    
    //Mostrando seleccion en el menú
    $('#registroSeminarista').addClass('selected-item');
    $('#registroDocentes').removeClass('selected-item');
    $('#controlDocentes').removeClass('selected-item');
    $('#opcionesSeminaristas').addClass('active');

    //Iniciando máscara para campos de texto
    var txtPhone = document.getElementById('txtTelefonoCasa');
    
    if(txtPhone != null) //Verificando  que exista un elemento con el id txtPhone
    {
        var maskOptions = {
            mask: '0000-0000'
        };
        var mask = IMask(txtPhone, maskOptions);
    }

    //Iniciando máscara para campos de texto
    var txtCellPhone = document.getElementById('txtCelular');
    
    if(txtCellPhone != null) //Verificando  que exista un elemento con el id txtPhone
    {
        var maskOptions = {
            mask: '0000-0000'
        };
        var mask = IMask(txtCellPhone, maskOptions);
    }

    //Iniciando máscara para campos de dui

    var txtDui = document.getElementById('txtDui');
    
    if(txtDui != null) //Verificando  que exista un elemento con el id txtPhone
    {
        var maskOptions = {
            mask: '00000000-0'
        };
        var mask = IMask(txtDui, maskOptions);
    }
    
});