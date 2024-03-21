//Este archivo inicializa las funciones de la vista index de estudiantes
document.addEventListener("DOMContentLoaded", function(event) {    

    //Mostrando selección de vista en menú
    $('#controlSeminaristas').addClass('selected-item');
    $('#registroDocentes').removeClass('selected-item');
    $('#controlDocentes').removeClass('selected-item');
    $('#registroSeminarista').removeClass('selected-item');
    $('#opcionesSeminaristas').addClass('active');

})

const generarReporteModal = () => {
    
    
    $('#generarReporte').modal('show');
}