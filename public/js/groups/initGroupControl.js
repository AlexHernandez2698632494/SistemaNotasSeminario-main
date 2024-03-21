document.addEventListener("DOMContentLoaded", function(event) {

    //Mostrando seleccion en el menú
    $('#controlGrupos').addClass('selected-item');
    $('#opcionesGrupo').addClass('active');
});

const openDeleteModal = (data) => {
    
    const elements = data.split(',');

    const studentName = elements[0];    
    const detailId = elements[1];
    const groupId = elements[2];

    const message = `¿Está seguro que desea eliminar al estudiante "${studentName}", del grupo de clase?`;

    $('#txtIdDetalleEliminar').val(detailId);
    $('#txtIdGrupo').val(groupId);
    $('#txtDeleteModal').text(message);

    $('#eliminarEstudiante').modal('show');
}