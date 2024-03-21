function validateOnlyNumbersOnInput(input){
    var re = /^([1-9]|[1-9][0-9]|[1-9][0][0])$/i;
    let msg = input.value;
    // Si el caracter introducido no es un número
    if(!(msg.match(re) !== null)){
        // Elimina el último caracter introducido
        input.value = msg.slice(0, msg.length - 1);
    }
}

function mostrarBotonAgregar(porcentaje){
    let btnAgregar = document.getElementById("btnAgregar");
    if(porcentaje == 100){
        btnAgregar.style.display = 'none';;
    }
    else {
        btnAgregar.style.display = '';
    }

}

const updateEvaluacionModal = (id) => {

    $.ajax({
        // la URL para la petición
        url : `http://127.0.0.1:8000/evaluacion/getEvaluacion/${id}`,            
        type : 'GET',        
        dataType : 'json',
            
        success : function(data) {
           const {nombreEvaluacion, porcentaje, descripcion, idEvaluacion} = data;
            $('#txtNombreEvaluacion').val(nombreEvaluacion);
            $('#txtPorcentaje').val(porcentaje);
            $('#txtDescripcion').val(descripcion);        
            $('#txtIdEvaluacionActualizar').val(idEvaluacion);
            
            $('#modificarEvaluacion').modal('show');  
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

    //$('#modificarEvaluacion').modal('show');
}

const confirmarEliminacion = (id) => {

    $.ajax({
        // la URL para la petición
        url : `http://127.0.0.1:8000/evaluacion/getEvaluacion/${id}`,            
        type : 'GET',        
        dataType : 'json',
            
        success : function(data) {
           const {nombreEvaluacion, porcentaje, fechaInicio, fechaFinalizacion, idEvaluacion} = data;
           const pregunta = "¿Está seguro que desea eliminar la evaluación "+ nombreEvaluacion+"?";
           document.getElementById('txtPregunta').innerHTML=pregunta;
            $('#txtIdEvaluacionEliminar').val(idEvaluacion);
            
            $('#eliminarEvaluacion').modal('show');  
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


