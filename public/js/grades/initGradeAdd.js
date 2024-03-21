function validateOnlyNumbersOnInput(input){
    var re = /^([1-9]|[1-9][0-9]|[1-9][0][0])$/i;
    let msg = input.value;
    // Si el caracter introducido no es un número
    if(!(msg.match(re) !== null)){
        // Elimina el último caracter introducido
        input.value = msg.slice(0, msg.length - 1);
    }
}