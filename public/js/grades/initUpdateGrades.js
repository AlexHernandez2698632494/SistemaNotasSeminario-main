const updateNotaModal = (id) => {

    $.ajax({
        // la URL para la petición
        url : `http://127.0.0.1:8000/teacherSite/getNota/${id}`,            
        type : 'GET',        
        dataType : 'json',
            
        success : function(data) {
            const {idNota, idEvaluacion, nombreEvaluacion, apellidoEstudiante, nombreEstudiante, nota} = data;
            $('#txtIdNotaActualizar').val(idNota);
            $('#txtIdEvaluacion').val(idEvaluacion);
            document.getElementById('txtActividad').innerHTML = "Actualización de calificación para actividad: "+nombreEvaluacion;
            document.getElementById('txtEstudiante').innerHTML = " de estudiante: "+apellidoEstudiante + ", " + nombreEstudiante;
            $('#txtNota').val(nota);
            $('#modificarNota').modal('show');  
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