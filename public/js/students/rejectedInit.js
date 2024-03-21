//Este archivo inicializa las funciones de la vista de seminaristas eliminados
document.addEventListener("DOMContentLoaded", function(event) {    

    //Mostrando selección de vista en menú
    $('#candidatosRechazados').addClass('selected-item');
    $('#opcionesSeminaristas').addClass('active');       
})

const openDeleteModal = (data) => {
    
    const elements = data.split(',');

    const idCandidate = elements[0];
    const nameCandidate = elements[1];

    const message = `¿Está seguro que desea eliminar el candidato "${nameCandidate}"?, no se podrá recuperar la información`;

    $('#txtCandidatoEliminar').val(idCandidate);
    $('#txtDeleteModal').text(message);
    $('#eliminarCandidato').modal('show');
}

const openAcceptModal = (data) => {

    const elements = data.split(',');

    const idCandidate = elements[0];
    const nameCandidate = elements[1];

    const message = `¿Está seguro que desea aceptar al candidato "${nameCandidate}"?`;

    $('#txtCandidatoAceptar').val(idCandidate);
    $('#txtAcceptModal').text(message);
    $('#aceptarCandidato').modal('show');
}