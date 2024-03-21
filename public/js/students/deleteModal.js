const openDeleteModal = (data) => {
    
    const elements = data.split(',');

    const studentId = elements[0];
    const studentName = elements[1];

    const message = `¿Está seguro que desea eliminar al estudiante "${studentName}"?`;

    $('#txtIdEstudianteEliminar').val(studentId);
    $('#txtDeleteModal').text(message);

    $('#eliminarEstudiante').modal('show');
}