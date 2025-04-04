let hiddenBody = document.getElementById('hidden-body');
let suggestionUl = document.getElementById('suggestions');

const contadorProductos = {
    productosAgregados: 0,
    actualizarContador: function() {
        document.getElementById('contador-productos').textContent = `Productos Agregados: ${this.productosAgregados}`;
        console.log(`Contador actualizado: ${this.productosAgregados}`);  
    },
    agregarProducto: function() {
        this.productosAgregados++;
        this.actualizarContador();
        console.log('Producto agregado. Total de productos:', this.productosAgregados);  

    },
    eliminarProducto: function() {
        if (this.productosAgregados > 0) {
            this.productosAgregados--;
            this.actualizarContador();
            console.log('Producto eliminado. Total de productos:', this.productosAgregados);  

        }
    }
    
};

document.getElementById('search').addEventListener('input', function () {
    let inputQuery = this;
    let query = inputQuery.value;

    if (query.length > 2) {
        let data = null;
        let xhr = new XMLHttpRequest();
        xhr.open('GET', `/ingresos/searchingresos?query=${query}`, true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                hiddenBody.style.display = 'block';
                data = JSON.parse(xhr.responseText);
                suggestionUl.innerHTML = ''; 

                data.forEach(item => {
                    let liItem = createLi(['list-group-item','hover-sistema-uno'], null);
                    liItem.style.cursor = 'pointer';

                    let rowItem = createDiv(['row'], null);

                    let colSerie = createDiv(['col-12'], null);
                    colSerie.textContent = item.numeroSerie;

                    let colProducto = createDiv(['col-12','text-secondary'], null);
                    colProducto.innerHTML = '<small>' + item.Producto.codigoProducto + '</small>';

                    rowItem.appendChild(colSerie);
                    rowItem.appendChild(colProducto);
                    liItem.appendChild(rowItem);

                    liItem.addEventListener('click', function(){
                        addProductoSerial(item, query);
                        suggestionUl.innerHTML = '';
                        hiddenBody.style.display = 'none';
                        inputQuery.value = item.numeroSerie;
                        
                    });

                    console.log("Agregando elemento", liItem);
                    suggestionUl.appendChild(liItem); 
                });
            }
        };
        xhr.send();
        return data;
    } else {
        suggestionUl.innerHTML = ''; 
        hiddenBody.style.display = 'none';
    }

});


function scanOperations(){
    searchCodeToController(getSerial());
}

function searchCodeToController(query) {
    let data = null;
    let xhr = new XMLHttpRequest();
    xhr.open('GET', `/ingresos/getoneingreso?query=${query}`, true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            data = JSON.parse(xhr.responseText);
            addProductoSerial(data,query);
        }
    };
    xhr.send();
    return data;
}

function addProductoSerial(object, query) {
    console.log(object);
    if (object == null || Object.keys(object).length == 0) {
        alertBootstrap('Producto ' + query + ' no encontrado', 'warning');
        return;
    }


    if (validateDuplicity(object.Registro.numeroSerie)) {
        alertBootstrap('Producto ' + object.Registro.numeroSerie + ' ya agregado', 'warning');
        return;
    }


    contadorProductos.agregarProducto(); 

    let ulTraslado = document.getElementById('lista-traslado');


    let itemTraslado = createLi(['list-group-item', 'item-traslado'], null);
    itemTraslado.setAttribute('data-serie', object.Registro.numeroSerie);

    let divRow = createDiv(['row', 'text-center'], null);

    let divColProducto = createDiv(['col-10', 'col-md-4', 'text-start'], null);
    divColProducto.innerHTML = "<strong>" + object.Producto.modelo + "</strong><br class='d-none d-md-inline'><small class='text-secondary d-none d-md-inline'>" + object.Producto.codigoProducto + "</small>";

    let divColLinkDelete = createDiv(['col-2', 'd-md-none', 'text-end'], null);
    let linkDelete = createLink(['text-danger'], null, '<i class="bi bi-x-lg"></i>', 'javascript:void(0)', [() => {
        itemTraslado.remove();
       
        contadorProductos.eliminarProducto();
        validateProductos();
    }]);
    divColLinkDelete.appendChild(linkDelete);

    let divColSerie = createDiv(['col-md-2', 'text-start', 'text-md-center'], null);
    divColSerie.innerHTML = '<small>' + object.Registro.numeroSerie + '</small><br class="d-none d-md-inline"><small class="text-secondary d-none d-md-inline">' + object.Proveedor.nombreProveedor + '</small>';

    let divColEstado = createDiv(['col-md-1', 'd-none', 'd-md-block'], null);
    divColEstado.innerHTML = '<small>' + object.Registro.estado + '</small>';

    let divColOrigen = createDiv(['col-6', 'col-md-2'], null);
    divColOrigen.innerHTML = '<small class="form-label d-md-none">Origen</small>' + "<select class='form-select form-select-sm' disabled>" +
        "<option selected>" + object.Almacen.descripcion + "</option>" +
        "</select>";

    let divColDestino = createDiv(['col-6', 'col-md-2'], null);
    let selectDestino = document.createElement('select');
    selectDestino.name = 'traslado[' + object.idRegistro + ']';
    selectDestino.classList.add('form-select', 'form-select-sm');
    let defaultOption = document.createElement('option');
    defaultOption.value = '';


    defaultOption.textContent = '-elige un destino-';
    selectDestino.appendChild(defaultOption);
    almacenes.forEach(almacen => {
        if (object.Almacen.idAlmacen != almacen.idAlmacen) {
            let optionDestino = document.createElement('option');
            optionDestino.value = almacen.idAlmacen;
            optionDestino.textContent = almacen.descripcion;
            selectDestino.appendChild(optionDestino);
        }
    });
    divColDestino.innerHTML = '<small class="form-label d-md-none">Destino</small>';
    divColDestino.appendChild(selectDestino);

    let divColBtnDelete = createDiv(['col-md-1', 'text-start', 'text-md-center', 'd-none', 'd-md-block'], null);
    let btnDelete = createButton(['btn', 'btn-danger', 'btn-sm'], null, '<i class="bi bi-trash-fill"></i>', 'button', [() => {
        itemTraslado.remove();
        contadorProductos.eliminarProducto();
        validateProductos();
    }]);
    divColBtnDelete.appendChild(btnDelete);

    divRow.appendChild(divColProducto);
    divRow.appendChild(divColLinkDelete);
    divRow.appendChild(divColSerie);
    divRow.appendChild(divColEstado);
    divRow.appendChild(divColOrigen);
    divRow.appendChild(divColDestino);
    divRow.appendChild(divColBtnDelete);
    itemTraslado.appendChild(divRow);
    ulTraslado.insertBefore(itemTraslado, ulTraslado.firstElementChild.nextSibling); 
    validateProductos();
}
function habilitarBotonReubicar() {
    const btnReubicar = document.getElementById('btn-reubicar-submit');
    const selectsDestino = document.querySelectorAll('.item-traslado select[name^="traslado"]');
    
    let habilitado = true;
    
    // Verifica si algún select tiene la opción 0 seleccionada
    selectsDestino.forEach(select => {
        if (select.value == '') {
            habilitado = false;  // Deshabilita el botón si algún destino es 0
        }
    });

    btnReubicar.disabled = !habilitado;
}

document.addEventListener('change', function (event) {
    if (event.target.matches('.item-traslado select[name^="traslado"]')) {
        habilitarBotonReubicar();
    }
});

// Función para mostrar el modal de confirmación
function mostrarModalConfirmacion(event) {
    event.preventDefault();  // Previene el envío inmediato del formulario
    const modal = document.getElementById('modalConfirmacion');
    const fondo = document.getElementById('hidden-body');
    modal.style.display = "block";  // Muestra el modal
    fondo.style.display = "block";  // Muestra el fondo semitransparente
}

function confirmarReubicacion(event) {
    event.preventDefault();  // Prevenir el comportamiento por defecto
    document.querySelector('form').submit(); 
}


document.addEventListener('DOMContentLoaded', function () {
    const btnReubicar = document.getElementById('btn-reubicar-submit');
    if (btnReubicar) {
        btnReubicar.addEventListener('click', mostrarModalConfirmacion);
    }

    const btnConfirmar = document.getElementById('btn-confirmar');
    if (btnConfirmar) {
        btnConfirmar.addEventListener('click', confirmarReubicacion);
    }

    const btnCancelar = document.getElementById('btn-cancelar');
    if (btnCancelar) {
        btnCancelar.addEventListener('click', function () {
            const modal = document.getElementById('modalConfirmacion');
            const fondo = document.getElementById('hidden-body');
            modal.style.display = "none";  
            fondo.style.display = "none";  
        });
    }
});

// Función para manejar la visibilidad de los productos
function validateProductos() {
    let itemsProductos = document.querySelectorAll('.item-traslado');
    let ulTraslado = document.getElementById('lista-traslado');
    let avisoVacio = document.getElementById('aviso-vacio');
    let btnReubicarContainer = document.getElementById('btn-reubicar-container');

    if (itemsProductos.length > 0) {
        ulTraslado.style.visibility = 'visible';
        ulTraslado.style.height = '70vh';
        avisoVacio.style.display = 'none';
        btnReubicarContainer.style.display = 'block';
        habilitarBotonReubicar(); 
    } else {
        ulTraslado.style.visibility = 'hidden';
        ulTraslado.style.height = '0';
        avisoVacio.style.display = 'block';
        btnReubicarContainer.style.display = 'none';
    }
}

function validateDuplicity(serial) {
    let itemsProductos = document.querySelectorAll('.item-traslado');
    if (itemsProductos.length > 0) {
        for (let i = 0; i < itemsProductos.length; i++) {
            if (itemsProductos[i].dataset.serie == serial) {
                return true;
            }
        }
    }
    return false;
}

function hideSuggestions(event) {
    let suggestions = document.getElementById('suggestions');
    let hiddenBody = document.getElementById('hidden-body');
    if (!suggestions.contains(event.target) && event.target.id !== 'search') {
        suggestions.innerHTML = ''; // Oculta las sugerencias
        hiddenBody.style.display = 'none';
    }
}

document.addEventListener('click', hideSuggestions);
