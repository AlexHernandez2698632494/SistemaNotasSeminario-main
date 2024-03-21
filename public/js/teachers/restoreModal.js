const openRestoreModal = (data) => {
    const elements = data.split(',');

    const teacherId = elements[0];
    const teacherName = elements[1];

    const message = `¿Está seguro que desea restaurar el docente "${teacherName}"?`

    $('#txtRestoreModal').text(message);
    $('#txtIdDocenteRestaurar').val(teacherId);
    
    $('#restaurarDocente').modal('show');
}