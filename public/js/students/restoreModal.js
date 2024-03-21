const openRestoreModal = (data) => {
    const elements = data.split(',');

    const studentId = elements[0];
    const studentName = elements[1];

    const message = `¿Está seguro que desea restaurar el seminarista "${studentName}"?`;

    $('#txtRestoreModal').text(message);
    $('#txtIdEstudianteRestaurar').val(studentId);
    
    $('#restaurarEstudiante').modal('show');
}