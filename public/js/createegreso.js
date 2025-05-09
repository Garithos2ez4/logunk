const validateIconSku = document.getElementById('sku-modal-egreso-validate');
const hiddenBody = document.getElementById('hidden-body');
const itemEgresoDiv = document.getElementById('div-items-create-egreso');
const btnSubmitCreateEgreso = document.getElementById('btn-create-egreso-submit');
var path = window.assetUrl;

const cartManager = {
    productosAgregados: 0,
    agregarProducto: function() {
        this.productosAgregados++;
        document.getElementById('contador-productos').textContent = `Productos Agregados: ${this.productosAgregados}`;
    },
    eliminarProducto: function() {
        if (this.productosAgregados > 0) {
            this.productosAgregados--;
            document.getElementById('contador-productos').textContent = `Productos Agregados: ${this.productosAgregados}`;
        }
    }
};

document.addEventListener('DOMContentLoaded', function() {
    const fechaActual = new Date().toISOString().split('T')[0];
    
    // Configuración inicial para fecha de pedido
    document.querySelector('[name="fechapedido"]').setAttribute('max', fechaActual);

    // Validación para fecha de pedido (2019)
    document.querySelector('[name="fechapedido"]').addEventListener('blur', function() {
        const fechaDespacho = document.querySelector('[name="fechadespacho"]');
        
        if(this.value) {
            const fechaPedido = new Date(this.value);
            
            if(isNaN(fechaPedido.getTime())) {
                alertBootstrap('Formato de fecha inválido', 'danger');
                this.classList.add('is-invalid');
                return;
            }

            if(fechaPedido.getFullYear() < 2019) {
                alertBootstrap('El año de pedido no puede ser menor a 2019', 'danger');
                this.classList.add('is-invalid');
                return;
            }
            
            this.classList.remove('is-invalid');
            fechaDespacho.setAttribute('min', this.value);
        }
        validateSubmit();
    });

    // Validación para fecha de despacho
    document.querySelector('[name="fechadespacho"]').addEventListener('blur', function() {
        const fechaPedido = document.querySelector('[name="fechapedido"]').value;
        const fechaDespachoValue = this.value;
        
        if(!fechaDespachoValue) return;

        const fechaDespacho = new Date(fechaDespachoValue);
        
        // Validación básica de formato
        if(isNaN(fechaDespacho.getTime())) {
            alertBootstrap('Formato de fecha inválido', 'danger');
            this.classList.add('is-invalid');
            return;
        }

        // Validación contra fecha de pedido
        if(fechaPedido) {
            const fechaPedidoObj = new Date(fechaPedido);
            
            if(fechaDespacho < fechaPedidoObj) {
                alertBootstrap('La fecha de despacho debe ser posterior al pedido', 'danger');
                this.classList.add('is-invalid');
                return;
            }
        }

        this.classList.remove('is-invalid');
        validateSubmit();
    });

    // Validación final al enviar
    document.querySelector('form').addEventListener('submit', function(event) {
        if (!validateFechas(event)) {
            event.preventDefault();
        }
    });
});

function validateFechas(event = null) {
    const fechaPedido = document.querySelector('[name="fechapedido"]');
    const fechaDespacho = document.querySelector('[name="fechadespacho"]');
    let isValid = true;

    // Validación año 2019
    if(fechaPedido.value) {
        const añoPedido = new Date(fechaPedido.value).getFullYear();
        if(añoPedido < 2019) {
            isValid = false;
            fechaPedido.classList.add('is-invalid');
            if(event) {
                alertBootstrap('El año de pedido no puede ser menor a 2019', 'danger');
            }
        }
    }

    // Validación secuencia temporal
    if(fechaPedido.value && fechaDespacho.value) {
        const fPedido = new Date(fechaPedido.value);
        const fDespacho = new Date(fechaDespacho.value);
        
        if(fDespacho < fPedido) {
            isValid = false;
            fechaDespacho.classList.add('is-invalid');
            if(event) {
                alertBootstrap('La fecha de despacho debe ser posterior al pedido', 'danger');
            }
        }
    }

    return isValid;
}

function alertBootstrap(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.classList.add('alert', 'alert-' + type, 'alert-dismissible', 'fade', 'show');
    alertDiv.setAttribute('role', 'alert');
    alertDiv.innerHTML = `<strong> dice:</strong> ${message}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>`;
    document.getElementById('form-container').appendChild(alertDiv);

    setTimeout(() => alertDiv.remove(), 1000);
}

window.cartManager = cartManager;

function searchPublicacion(inputElement) {
    let query = inputElement.value;

    function handleClickOutside(event) {
        let suggestions = document.getElementById('suggestions-sku');
        if (!suggestions.contains(event.target) && event.target !== inputElement) {
            suggestions.innerHTML = ''; // Limpiar sugerencias si se hace clic fuera del input
            hiddenBody.style.display = 'none';
        }
    }

    document.removeEventListener('click', handleClickOutside);
    document.addEventListener('click', handleClickOutside);

    if (query.length > 2) { // Comenzar la búsqueda después de 3 caracteres
        document.getElementById('hidden-publicacion-sku').value = "";
        let xhr = new XMLHttpRequest();
        xhr.open('GET', `/searchpublicacion?query=${query}`, true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                let data = JSON.parse(xhr.responseText);
                let suggestions = document.getElementById('suggestions-sku');
                hiddenBody.style.display = 'block';
                inputElement.style.zIndex = '1000';
                suggestions.innerHTML = '';

                data.forEach(item => {
                    let li = document.createElement('li');
                    li.classList.add('list-group-item', 'pe-0');
                    li.classList.add('hover-sistema-uno', 'text-truncate');
                    li.style.cursor = "pointer";

                    let divRow = document.createElement('div');
                    divRow.classList.add('row', 'w-100');

                    let colSerie = document.createElement('div');
                    colSerie.classList.add('col-md-8');
                    colSerie.textContent = item.sku;

                    let colAlmacen = document.createElement('div');
                    colAlmacen.classList.add('col-md-4', 'text-end');
                    colAlmacen.textContent = item.fechaPublicacion;

                    let colProducto = document.createElement('div');
                    colProducto.classList.add('col-md-12');
                    let smallProducto = document.createElement('em');
                    smallProducto.textContent = item.titulo;
                    smallProducto.style.fontSize = '12px';
                    colProducto.appendChild(smallProducto);

                    divRow.appendChild(colSerie);
                    divRow.appendChild(colAlmacen);
                    divRow.appendChild(colProducto);
                    li.appendChild(divRow);

                    li.addEventListener('click', function () {
                        inputElement.value = item.sku;
                        document.getElementById('hidden-publicacion-sku').value = item
                            .idPublicacion;
                        hiddenBody.style.display = 'none';
                        inputElement.style.zIndex = '1';
                        suggestions.innerHTML = ''; // Limpiar sugerencias después de seleccionar una
                        validateIconSku.classList.remove('bi-exclamation-circle', 'text-danger');
                        validateIconSku.classList.add('bi-check-circle', 'text-success');
                        validateSubmit();
                    });

                    suggestions.appendChild(li);
                });
            }
        };
        xhr.send();
    } else {
        document.getElementById('suggestions-sku').innerHTML = ''; // Limpiar si hay menos de 3 caracteres
        document.getElementById('hidden-publicacion-sku').value = "";
        validateIconSku.classList.add('bi-exclamation-circle', 'text-danger');
        validateIconSku.classList.remove('bi-check-circle', 'text-success');
        hiddenBody.style.display = 'none';
        inputElement.style.zIndex = '1';
    }
}

function checkSku() {
    let checkSku = document.getElementById('check-sku-egreso');
    let inputEgreso = document.getElementById('input-sku-egreso');
    let hiddenEgreso = document.getElementById('hidden-publicacion-sku');
    let inputNumberOrder = document.getElementById('input-numero-orden');

    if (checkSku.checked) {
        inputEgreso.disabled = true;
        inputEgreso.value = checkSku.value;
        inputNumberOrder.disabled = true;
        inputNumberOrder.value = checkSku.value;
        hiddenEgreso.value = 'NULO';
        validateIconSku.classList.remove('bi-exclamation-circle', 'text-danger');
        validateIconSku.classList.add('bi-check-circle', 'text-success');
    } else {
        inputEgreso.disabled = false;
        inputEgreso.value = '';
        inputNumberOrder.disabled = false;
        inputNumberOrder.value = '';
        hiddenEgreso.value = '';
        validateIconSku.classList.add('bi-exclamation-circle', 'text-danger');
        validateIconSku.classList.remove('bi-check-circle', 'text-success');
    }
}

let productosAgregados = [];

function searchRegistro(inputElement) {
    let query = inputElement.value;

    function handleClickOutside(event) {
        let suggestions = document.getElementById('suggestions-serial-number');
        if (!suggestions.contains(event.target) && event.target !== inputElement) {
            suggestions.innerHTML = ''; // Limpiar sugerencias si se hace clic fuera del input
            hiddenBody.style.display = 'none';
        }
    }

    // Agregar el manejador de clics al documento
    document.addEventListener('click', handleClickOutside);

    if (query.length > 2) { // Comenzar la búsqueda después de 3 caracteres
        document.getElementById('hidden-product-serial-number').value = "";
        let xhr = new XMLHttpRequest();
        xhr.open('GET', `/egresos/searchregistro?query=${query}`, true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                let data = JSON.parse(xhr.responseText);
                let suggestions = document.getElementById('suggestions-serial-number');
                hiddenBody.style.display = 'block';
                inputElement.style.zIndex = '1000';
                suggestions.innerHTML = '';


                data.forEach(item => {
                    
                    if (productosAgregados.includes(item.idRegistroProducto)){
                        return;
                    }
                    
                    let li = document.createElement('li');
                    li.classList.add('list-group-item', 'pe-0');
                    li.classList.add('hover-sistema-uno', 'text-truncate');
                    li.style.cursor = "pointer";

                    let divRow = document.createElement('div');
                    divRow.classList.add('row', 'w-100');

                    let colSerie = document.createElement('div');
                    colSerie.classList.add('col-md-8');
                    colSerie.textContent = item.numeroSerie;

                    let colAlmacen = document.createElement('div');
                    colAlmacen.classList.add('col-md-4', 'text-end');
                    colAlmacen.textContent = item.almacen;

                    let colProducto = document.createElement('div');
                    colProducto.classList.add('col-md-12');
                    let smallProducto = document.createElement('em');
                    smallProducto.textContent = item.nombreProducto;
                    smallProducto.style.fontSize = '12px';
                    colProducto.appendChild(smallProducto);

                    divRow.appendChild(colSerie);
                    divRow.appendChild(colAlmacen);
                    divRow.appendChild(colProducto);
                    li.appendChild(divRow);

                    li.addEventListener('click', function () {
                        inputElement.value = item.numeroSerie;
                        document.getElementById('hidden-product-serial-number').value = item
                            .idRegistroProducto;
                        suggestions.innerHTML =''; 
                        hiddenBody.style.display = 'none';
                        inputElement.style.zIndex = '1';
                        createItem(item,query);
                        validateSubmit();
                    });

                    suggestions.appendChild(li);
                });
            }
        };
        xhr.send();
    } else {
        document.getElementById('suggestions-serial-number').innerHTML = ''; // Limpiar si hay menos de 3 caracteres
        document.getElementById('hidden-product-serial-number').value = "";
        hiddenBody.style.display = 'none';
        inputElement.style.zIndex = '1';
    }
}

function createItem(object, query){
    if (object == null || Object.keys(object).length == 0) {
        alertBootstrap('Producto ' + query + ' no encontrado', 'warning');
        return;
    }

    if (validateSerialById(object.idRegistroProducto)) {
        alertBootstrap('Producto ' + object.numeroSerie + ' ya agregado', 'warning');
        return;
    }

    productosAgregados.push(object.idRegistroProducto);

    let divRowItem = createDiv(['row','pt-2','pb-2','border'], null);
    let inputHidden = createInput(['body-form','hidden-form'], null, 'hidden', object.idRegistroProducto, 'idregistros[]');
    let divColImg = createDiv(['col-1'], null);
    let divColContent = createDiv(['col-11'], null);
    let divRowContent = createDiv(['row'], null);

    let imgItem = document.createElement('img');
    imgItem.classList.add('w-100', 'border');
    imgItem.style.width = '100%';
    imgItem.src = path + '/' + object.image;
    divColImg.appendChild(imgItem);

    let divColTitle = createDiv(['col-10', 'pt-2'], null);
    let h4Title = createH5(null, null, object.nombreProducto);
    divColTitle.appendChild(h4Title);

    let divColBtnDelete = createDiv(['col-2', 'text-end'], null);
    let btnDeleteItem = createLink(
        ['text-danger', 'fs-4'],
        null,
        '<i class="bi bi-x-lg"></i>',
        'javascript:void(0)',
        [
            () => {
                // Aquí se elimina el producto y se actualiza el contador
                divRowItem.remove(); // Elimina el producto del DOM
                cartManager.eliminarProducto(); // Resta del contador
                validateSubmit(); // Validamos el estado del formulario

                productosAgregados = productosAgregados.filter(id => id !== object.idRegistroProducto);

            }
        ]
    );
    divColBtnDelete.appendChild(btnDeleteItem);

    let divColModelo = createDiv(['col-4'], null);
    divColModelo.innerHTML = 'Modelo: ' + object.modelo;

    let divColCodigo = createDiv(['col-3'], null);
    divColCodigo.innerHTML = 'Codigo: ' + object.codigoProducto;

    let divColSerial = createDiv(['col-3'], null);
    divColSerial.innerHTML = 'SN: ' + object.numeroSerie;

    let divColEstado = createDiv(['col-2', 'text-end'], null);

    divColEstado.innerHTML = object.estado;
    divRowContent.appendChild(divColTitle);
    divRowContent.appendChild(divColBtnDelete);
    divRowContent.appendChild(divColModelo);
    divRowContent.appendChild(divColCodigo);
    divRowContent.appendChild(divColSerial); 
    divRowContent.appendChild(divColEstado);
    divColContent.appendChild(divRowContent);
    divRowItem.appendChild(divColImg);
    divRowItem.appendChild(inputHidden);
    divRowItem.appendChild(divColContent);
    itemEgresoDiv.insertBefore(divRowItem, itemEgresoDiv.firstChild);


    cartManager.agregarProducto();
}

function validateSerialById(id) {
    let inputHidden = document.querySelectorAll('.hidden-form');

    return Array.from(inputHidden).some(function(x) {
        return x.value == id;
    });
}


function validateSubmit() {
    let validate = true;
    let inputsCab = document.querySelectorAll('.cab-form');
    let inputBody = document.querySelectorAll('.body-form');

    inputsCab.forEach(function(x) {
        if (x.value === '') {
            validate = false; 
        }
    });

    if(inputBody.length < 1){
        validate = false; 
    }
    const fechasValidas = validateFechas(); 
    if (!fechasValidas) {
        validate = false;
    }
    const fechaPedidoInput = document.querySelector('[name="fechapedido"]');
    if (fechaPedidoInput && fechaPedidoInput.value) {
        const anio = new Date(fechaPedidoInput.value).getFullYear();
        if (anio < 2019) {
            validate = false;
        }
    }

    btnSubmitCreateEgreso.disabled = !validate; 
}

function scanOperations(){
    searchCodeToController(getSerial());
}

function searchCodeToController(query) {
    let data = null;
    let xhr = new XMLHttpRequest();
    xhr.open('GET', `/egresos/getoneegreso?query=${query}`, true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            data = JSON.parse(xhr.responseText);
            createItem(data,query);
        }
    };
    xhr.send();
    return data;
}


document.getElementById('check-sku-egreso').addEventListener('change', checkSku);
document.getElementById('check-sku-egreso').addEventListener('change', validateSubmit);

document.addEventListener('DOMContentLoaded',function(){
    document.querySelectorAll('input').forEach(function(x){
        x.addEventListener('input',validateSubmit);
    });
    validateSubmit();
})
