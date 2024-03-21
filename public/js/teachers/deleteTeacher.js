const openDeleteTeacherModal = ( value ) => {
    $('#eliminarDocente').modal('show');
    
    const elements = value.split(',');
    
    const teacherId = elements[0];
    const teacherName = elements[1];

    const message = `¿Está seguro que desea eliminar al docente "${teacherName}"?`;

    $('#txtIdDocenteEliminar').val(teacherId);
    $('#txtDeleteModal').text(message);

}