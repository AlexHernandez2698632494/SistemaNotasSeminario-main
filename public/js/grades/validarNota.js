const txtNotas =  document.querySelectorAll("#txtNota");
for(let i=0; i<txtNotas.length;i++){
    const validarNota = function (e){
        if(txtNotas[i].value.length == 0){
            let validarNota = /^[0-9]{1}$/;
            let tecla = e.key;
            if(!validarNota.test(tecla)){ e.preventDefault();}
        }
        else if(txtNotas[i].value.length == 1){
            if(txtNotas[i].value == 1){
                let validarNota = /^[0.]{1}$/;
                let tecla = e.key;
                if(!validarNota.test(tecla)){ e.preventDefault();}
            }
            else {
                let validarNota = /^[.]{1}$/;
                let tecla = e.key;
                if(!validarNota.test(tecla)){ e.preventDefault();}
            }    
        }
        else {
            if(txtNotas[i].value == 10){
                let validarNota = /^[]{1}$/;
                let tecla = e.key;
                if(!validarNota.test(tecla)){ e.preventDefault();}
            }
            else{
                let validarNota = /^[0-9]{1}$/;
                let tecla = e.key;
                if(!validarNota.test(tecla)){ e.preventDefault();}
            }
        }
    };
    txtNotas[i].addEventListener("keypress", validarNota);
}