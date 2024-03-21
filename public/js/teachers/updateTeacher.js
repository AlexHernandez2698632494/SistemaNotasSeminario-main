document.addEventListener("DOMContentLoaded", function(event) {   

    //Iniciando máscara para campos de texto
    var txtCellPhone = document.getElementById('txtTelefonoDocente');
    
    if(txtCellPhone != null) //Verificando  que exista un elemento con el id txtPhone
    {
        var maskOptions = {
            mask: '0000-0000'
        };
        var mask = IMask(txtCellPhone, maskOptions);
    }

    //Iniciando máscara para campos de dui

    var txtDui = document.getElementById('txtDuiDocente');
    
    if(txtDui != null) //Verificando  que exista un elemento con el id txtPhone
    {
        var maskOptions = {
            mask: '00000000-0'
        };
        var mask = IMask(txtDui, maskOptions);
    }
    
});

const openDeleteSubjectModal = (value) => {
    $('#eliminarMateria').modal('show'); 
    
    const elements = value.split(',');
    const message = `¿Está seguro que desea eliminar la materia "${elements[0]}"?`;
    
    $('#txtDeleteModal').text(message) 
    $('#txtIdDetalleEliminar').val(elements[1]);        
    $('#txtIdTeacher').val(elements[2]);        
}


const openDeleteTitleModal = (value) => {
    $('#eliminarTitulo').modal('show'); 
    
    const elements = value.split(',');
    const message = `¿Está seguro que desea eliminar el título "${elements[0]}"?`;
    
    $('#txtDeleteTitleModal').text(message) 
    $('#txtIdDetalleTituloEliminar').val(elements[1]);        
    $('#txtIdTeacherTitle').val(elements[2]);            
}

const updateTeacherModal = (id) => {

    $.ajax({
        // la URL para la petición
        url : `http://127.0.0.1:8000/teacher/getTeacher/${id}`,            
        type : 'GET',        
        dataType : 'json',
            
        success : function(data) {
            const {nombreDocente, apellidoDocente, duiDocente, correoDocente, numeroTelefono, idDocente} = data;
            $('#txtNombreDocente').val(nombreDocente);
            $('#txtApellidoDocente').val(apellidoDocente);
            $('#txtDuiDocente').val(duiDocente);
            $('#txtCorreoDocente').val(correoDocente);          
            $('#txtTelefonoDocente').val(numeroTelefono);           
            $('#txtIdDocente').val(idDocente);
            
            $('#updateStudent').modal('show');                        
        },
    
        // código a ejecutar si la petición falla;
        // son pasados como argumentos a la función
        // el objeto de la petición en crudo y código de estatus de la petición
        error : function(xhr, status) {
            // alert('Disculpe, existió un problema');
            swal({
                title: "Error",
                text: "Ha ocurrido un error al mostrar los datos, pongase en contacto con el administrador",
                icon: "error",
                button: "OK",
            })
        },       
    });

    $('#modificarDocente').modal('show');
}