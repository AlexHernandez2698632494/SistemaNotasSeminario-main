const endPeriod = (data) => {
    const elements = data.split(',');

    const periodId = elements[0];
    const periodName = elements[1];

    const message = `¿Está seguro que desea finalizar el ciclo ${periodName}?, no podrá volver a iniciarlo`;

    $('#txtFinalizacion').text(message);
    $('#txtIdCicloFinalizar').val(periodId);

    $('#endPeriod').modal('show');
}

const updatePeriod = (data) => {
    
    const elements = data.split(',');

    const periodId = elements[0];
    const periodName = elements[1];
    const startDate = elements[2];
    const endDate = elements[3];

    $('#txtNombreCiclo').val(periodName);
    $('#txtFechaInicio').val(startDate);
    $('#txtFechaFinalizacion').val(endDate);
    $('#txtIdCicloActualizar').val(periodId);
    
    $('#updatePeriod').modal('show');
}

const startPeriod = (data) => {
    
    const elements = data.split(',');

    const periodId = elements[0];
    const periodName = elements[1];

    const message = `¿Está seguro que desea iniciar el ciclo ${periodName}?`;


    $('#startMessage').text(message);
    $('#txtIdCicloIniciar').val(periodId);
    
    $('#startPeriod').modal('show');
}