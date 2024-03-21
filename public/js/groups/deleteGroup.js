const openDeleteModal = (data) => {
    
    $('#txtIdGrupoEliminar').val(data);
    $('#txtDeleteModalGroup').text('¿Está seguro que desea eliminar el grupo, ya no se podrá recuperar la información?');
    $('#eliminarGrupo').modal('show');
}

const openDeleteStudentModal = (data) => {
    var arrayDeCadenas = data.split(",");
    $('#txtIdDetalleEliminar').val(arrayDeCadenas[1]);
    $('#txtIdGrupo').val(arrayDeCadenas[2]);
    $('#txtDeleteModal').text('¿Está seguro que desea eliminar el estudiante '+arrayDeCadenas[0]+'?');
    $('#eliminarEstudiante').modal('show');
}