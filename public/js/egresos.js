const rowSerialVacio = document.getElementById('row-product-serial-number-vacio');
const rowSerialExists = document.getElementById('row-product-serial-number-exists');

function validateEgreso() {
    let inputsEgreso = document.querySelectorAll('.input-egreso');
    let disabledInput = false;

    inputsEgreso.forEach(function (x) {
        if (x.value == '') {
            disabledInput = true;
        }
    });

    return disabledInput;
}

/*function handleBtnRegistrar() {
    //let btnRegEgreso = document.getElementById('btn-create-egreso-submit');

    btnRegEgreso.disabled = validateEgreso();
}

//document.addEventListener('DOMContentLoaded', function () {
    handleBtnRegistrar();
});


document.getElementById('check-sku-egreso').addEventListener('change', handleBtnRegistrar);*/

let allInputsEgreso = document.querySelectorAll('.input-egreso');
allInputsEgreso.forEach(function (x) {
    x.addEventListener('input', handleBtnRegistrar);
});


function updateDataRowSerial(object){
    let imagen = rowSerialExists.querySelector('img');
    let title =  rowSerialExists.querySelector('h6');
    let modelo = rowSerialExists.querySelector('span');
    let codigo = rowSerialExists.querySelector('.cod');
    let estado = rowSerialExists.querySelector('p');

    imagen.src = path + '/' + object.image;

    title.textContent = object.nombreProducto;
    modelo.textContent = object.modelo;
    codigo.textContent = object.codigoProducto;
    estado.textContent = object.estado;
}


function searchEgreso(inputElement) {
    let query = inputElement.value;
    let hiddenBody = document.getElementById('hidden-body');
    function handleClickOutside(event) {
        let suggestions = document.getElementById('suggestions-egresos');
        if (!suggestions.contains(event.target) && event.target !== inputElement) {
            suggestions.innerHTML = ''; // Limpiar sugerencias si se hace clic fuera del input
            hiddenBody.style.display = 'none';
        }
    }

    // Agregar el manejador de clics al documento
    document.addEventListener('click', handleClickOutside);

    if (query.length > 2) { // Comenzar la búsqueda después de 3 caracteres
        let xhr = new XMLHttpRequest();
        xhr.open('GET', `/egresos/searchegreso?query=${query}`, true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                let data = JSON.parse(xhr.responseText);
                let suggestions = document.getElementById('suggestions-egresos');
                hiddenBody.style.display = 'block';
                suggestions.innerHTML = '';

                data.forEach(item => {
                    let li = document.createElement('li');
                    li.classList.add('list-group-item', 'pe-0');
                    li.classList.add('hover-sistema-uno', 'text-truncate');
                    li.style.cursor = "pointer";

                    let divRow = document.createElement('div');
                    divRow.classList.add('row', 'w-100');

                    let colSerie = document.createElement('div');
                    colSerie.classList.add('col-md-12');
                    colSerie.textContent = item.numeroSerie;

                    let colProducto = document.createElement('div');
                    colProducto.classList.add('col-md-12');
                    let smallProducto = document.createElement('em');
                    smallProducto.textContent = item.codigoProducto;
                    smallProducto.style.fontSize = '12px';
                    colProducto.appendChild(smallProducto);

                    divRow.appendChild(colSerie);
                    divRow.appendChild(colProducto);
                    li.appendChild(divRow);

                    li.addEventListener('click', function () {
                        inputElement.value = item.numeroSerie;
                        suggestions.innerHTML =''; 
                        viewModalEgreso(item);
                    });

                    suggestions.appendChild(li);
                    
                });
            }
        };
        xhr.send();
    } else {
        document.getElementById('suggestions-egresos').innerHTML = '';
        hiddenBody.style.display = 'none';
    }
}

document.getElementById('month').addEventListener('change', function () {
    let selectedMonth = this.value;

    if (selectedMonth == "") {
        alert('Fecha no valida.');
    } else {
        let url = "/egresos/" + selectedMonth;
        console.log(selectedMonth);

        window.location.href = url;
        
    }
});

document.getElementById('month').addEventListener('keydown', function (event) {
    // Evita que la acci贸n de borrado ocurra si se presiona Backspace o Delete
    if (event.key === 'Backspace' || event.key === 'Delete') {
        event.preventDefault();
    }
});

function viewModalEgreso(json){
    let modalEgreso = new bootstrap.Modal(document.getElementById('detailEgresoModal'));
    let hiddenIdEgreso = document.getElementById('modal-egreso-id');
    let labelTitulo = document.getElementById('modal-egreso-titulo');
    let labelSerialNumber = document.getElementById('modal-egreso-serialnumber');
    let labelEstado = document.getElementById('modal-egreso-estado');
    let labelUsuario = document.getElementById('modal-egreso-usuario');
    let divFecha = document.getElementById('modal-egreso-fecha');
    let divPublicacion = document.getElementById('modal-egreso-publicidad');
    let textAreaObservacion = document.getElementById('modal-egreso-observacion');
    let btnDevolucionEgreso = document.getElementById('modal-egreso-btn-devolucion');

    labelSerialNumber.textContent = json.numeroSerie;
    labelEstado.textContent = json.estado;
    labelTitulo.textContent = json.nombreProducto;
    textAreaObservacion.value = json.observacion;
    labelUsuario.textContent = json.usuario;
    hiddenIdEgreso.value = json.idEgreso;

    if(json.estado == 'DEVOLUCION'){
        btnDevolucionEgreso.style.display = 'none';
    }else{
        btnDevolucionEgreso.style.display = 'block';
    }

    if(json.estado == 'ENTREGADO'){
        divFecha.innerHTML = '<p class="mb-0"><small><strong>Fecha Compra:</strong> '+stringDate(json.fechaCompra)+'</small></p>'+
                            '<p class="mt-0 mb-0"><small><strong>Fecha Despacho:</strong> '+stringDate(json.fechaDespacho)+'</small></p>';
    }else{
        divFecha.innerHTML = '<p class="mb-0"><small><strong>Fecha Devolución:</strong> '+stringDate(json.fechaMovimiento)+'</small></p>';
    }

    if(json.cuenta == null){
        divPublicacion.innerHTML = '<label class="fw-bold">Publicacion:</label>' + '<p class="text-secondary mb-1">Sin publicación</p>';
    }else{
        divPublicacion.innerHTML = '<label class="fw-bold">Publicacion:</label>'+
                                    '<div class="row border rounded-3 ms-1 me-1 pt-2">'+
                                        '<div class="col-9">' +
                                            '<h6>'+json.cuenta+'</h6>' +
                                        '</div>'+
                                        '<div class="col-3">'+
                                            '<img src="'+ json.imagenPublicacion +'" alt="imagen" class="w-100 rounded-3">' +
                                        '</div>'+
                                        '<div class="col-6">'+
                                            '<label class="text-secondary"><small>sku:</small></label>'+
                                            '<p class="mb-1 pt-0"><small>'+ json.sku +'</small></p>'+
                                        '</div>'+
                                        '<div class="col-6 text-end">'+
                                            '<label class="text-secondary"><small>Nro de Orden:</small></label>'+
                                            '<p class="mb-1 pt-0"><small>'+json.numeroOrden +'</small></p>'+
                                        '</div>'+
                                    '</div>';
    }

    modalEgreso.show();
}

function formDetailEgreso(transaction){
    let formEgreso =  document.getElementById('form-detail-egreso');
    let hiddenTransaction = document.getElementById('modal-egreso-transaccion');

    hiddenTransaction.value = transaction;
    formEgreso.submit();
}

function hiddeInputDate(inputId) {
    let inputElement = document.getElementById(inputId);
    
    // Alternar visibilidad del input
    if (inputElement.style.display === "none" || inputElement.style.display === "") {
        inputElement.style.display = "block"; // Muestra el campo de fecha
    } else {
        inputElement.style.display = "none"; // Oculta el campo de fecha
    }
}


function stringDate(date){
    let fecha = new Date(date);
    let day = fecha.getDate().toString().padStart(2, '0');
    let month = (fecha.getMonth() + 1).toString().padStart(2, '0');
    let year = fecha.getFullYear();

    return `${day}/${month}/${year}`;
} 


