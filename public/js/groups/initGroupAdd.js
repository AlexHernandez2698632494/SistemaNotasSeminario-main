document.addEventListener("DOMContentLoaded", function(event) {

    //Mostrando seleccion en el menú
    $('#registroGrupos').addClass('selected-item');
    $('#opcionesGrupo').addClass('active');
});

//Función que muestra a los docentes que pueden impartir la materia seleccionada
const docentesMateria = (id) => {    
    
    $('#selectdocente').val(0).trigger('change');

    if(id > 0)
    {
        $.ajax({
            // la URL para la petición
            url : `http://127.0.0.1:8000/group/teacherSubject/${id}`,            
            type : 'GET',        
            dataType : 'json',
                
            success : function(data) {
                var selectDocente = $('#selectdocente');

                // Limpia todas las opciones actuales en el select
                selectDocente.empty();

                // Agrega una opción por defecto
                selectDocente.append('<option value="0">Seleccione docente</option>');

                // Agrega las opciones de docentes desde los datos obtenidos
                data.forEach(docente => {                    
                    selectDocente.append('<option value="' + docente.idDocente + '">' + docente.nombreDocente + ' ' + docente.apellidoDocente + '</option>');
                });
                
                
                selectDocente.trigger('change');
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
        

}