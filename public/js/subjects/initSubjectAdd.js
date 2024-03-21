document.addEventListener("DOMContentLoaded", function(event) {

    //Mostrando seleccion en el menú
    $('#registroMaterias').addClass('selected-item');
    $('#opcionesMateria').addClass('active');
});


const getPhaseDuration = (id) => {

    if(id != 0){
        $.ajax({
            // la URL para la petición
            url : `http://127.0.0.1:8000/subject/getDuration/${id}`,
            type : 'GET',
            dataType : 'json',

            success : function(data) {
                const {duracionanios, anioinicio, aniofinalizacion} = data[0];

                $('#txtAnioCarrera').empty();
                for(i=anioinicio; i<=aniofinalizacion; i++ ){
                    $('#txtAnioCarrera').append(`<option value=${i}>${i}</option>`)
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

const getNivel = () => {

    // const levels = [
    //     { name: "E1A1Cuatrimestre 1", level:"1"},
    //     { name: "E1A1Cuatrimestre 2", level:"2"},
    //     { name: "E2A2Cuatrimestre 1", level:"3"},
    //     { name: "E2A2Cuatrimestre 2", level:"4"},
    //     { name: "E2A3Cuatrimestre 1", level:"5"},
    //     { name: "E2A3Cuatrimestre 2", level:"6"}
    // ]
    
    // const phase = $('#selectGrado').val();
    // const year = $('#txtAnioCarrera').val();
    // const period = $('#selectCuatrimestre').val();

    // const phaseName = "E"+phase+"A"+year+period;

    // const selectedLevel = levels.find(({name}) => name === phaseName);

    // const {level} = selectedLevel;
    
    // $('#txtNivel').val(level);
    
}


