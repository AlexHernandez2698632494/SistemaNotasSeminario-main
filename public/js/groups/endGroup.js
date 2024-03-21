const showModalEndGroup = (element) => {

    const id = element.getAttribute('data-id');
    const nombre = element.getAttribute('data-nombre');
    const materia = element.getAttribute('data-materia');

    const mensaje = `¿Está seguro que desea finalizar el grupo ${materia} (${nombre})? El grupo no podrá volver a iniciarse.`;
    $('#txtEndModal').text(mensaje);
    $('#txtIdGrupoFinalizar').val(id);
    $('#finalizarGrupo').modal('show');
}