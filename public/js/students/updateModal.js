const openUpdateModal = (id) => {
    
    $.ajax({
        // la URL para la petición
        url : `http://127.0.0.1:8000/student/getStudent/${id}`,            
        type : 'GET',        
        dataType : 'json',
            
        success : function(data) {
            const {nombreEstudiante, apellidoEstudiante, duiEstudiante, correoEstudiante, numeroMovil, numeroTelefonicoCasa, direccion, enfermedades, idEstudiante} = data;
            $('#txtNombreEstudiante').val(nombreEstudiante);
            $('#txtApellidoEstudiante').val(apellidoEstudiante);
            $('#txtDui').val(duiEstudiante);
            $('#txtCorreo').val(correoEstudiante);
            $('#txtCelular').val(numeroMovil);
            $('#txtCelular').val(numeroMovil);
            $('#txtTelefonoCasa').val(numeroTelefonicoCasa);
            $('#txtDireccion').val(direccion);
            $('#txtEnfermedades').val(enfermedades);
            $('#txtIdEstudiante').val(idEstudiante);
            
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
}