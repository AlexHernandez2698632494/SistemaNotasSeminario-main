document.addEventListener("DOMContentLoaded", function(event) {

    
    //Mostrando seleccion en el menú
    $('#materiasEliminadas').addClass('selected-item');
    $('#opcionesMateria').addClass('active');

});

const openRestoreModal = (id) => {

    $.ajax({
        // la URL para la petición
        url : `http://127.0.0.1:8000/subject/getMateria/${id}`,            
        type : 'GET',        
        dataType : 'json',
            
        success : function(data) {
           const {idMateria, nombreMateria} = data;
           const pregunta = "¿Está seguro que desea restaurar la materia "+ nombreMateria+"?";
           document.getElementById('txtPregunta').innerHTML=pregunta;
            $('#txtIdMateriaRestaurar').val(idMateria);
            
            $('#restaurarMateria').modal('show');  
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