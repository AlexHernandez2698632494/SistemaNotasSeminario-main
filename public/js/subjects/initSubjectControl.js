document.addEventListener("DOMContentLoaded", function(event) {

    
    //Mostrando seleccion en el menú
    $('#controlMaterias').addClass('selected-item');
    $('#opcionesMateria').addClass('active');

});

const updateMateriaModal = (id) => {

    $.ajax({
        // la URL para la petición
        url : `http://127.0.0.1:8000/subject/getMateria/${id}`,            
        type : 'GET',        
        dataType : 'json',
            
        success : function(data) {
           const {idMateria, nombreMateria, cuatrimestre, anio, idEtapa} = data;
            $('#txtNombreMateria').val(nombreMateria);
            $('#selectCuatrimestre').val(cuatrimestre);
            $('#selectGrado').val(idEtapa);
            getPhaseDurationA(idEtapa,anio);
            
            $('#txtIdMateriaActualizar').val(idMateria);
            
            $('#modificarMateria').modal('show');  
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

const getPhaseDurationA = (id,anio) => {

    if(id != 0){
        $.ajax({
            // la URL para la petición
            url : `http://127.0.0.1:8000/subject/getDuration/${id}`,
            type : 'GET',
            dataType : 'json',

            success : function(data) {
                const {duracionanios, anioinicio, aniofinalizacion} = data[0];

                $('#selectAnio').empty();
                for(i=anioinicio; i<=aniofinalizacion; i++ ){
                    if(i==anio){
                        $('#selectAnio').append(`<option value=${i} selected>${i}</option>`);
                    } else
                    $('#selectAnio').append(`<option value=${i}>${i}</option>`);
                }
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
    }else{
        $('#txtAnioCarrera').empty();
        swal({
            title: "Información",
            text: "Debe de seleccionar una etapa",
            icon: "info",
            button: "OK",
        })
    }

}

const confirmarEliminacion = (id) => {

    $.ajax({
        // la URL para la petición
        url : `http://127.0.0.1:8000/subject/getMateria/${id}`,            
        type : 'GET',        
        dataType : 'json',
            
        success : function(data) {
           const {nombreMateria, idMateria} = data;
           const pregunta = "¿Está seguro que desea eliminar la materia "+ nombreMateria+"?";
           document.getElementById('txtPregunta').innerHTML=pregunta;
            $('#txtIdMateriaEliminar').val(idMateria);
            
            $('#eliminarMateria').modal('show');  
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