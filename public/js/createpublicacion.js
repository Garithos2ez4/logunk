function alertBootstrap(message, type) {
    var alertDiv = document.createElement('div');
    alertDiv.classList.add('alert', 'alert-' + type, 'alert-dismissible', 'fade', 'show');
    alertDiv.setAttribute('role', 'alert');
    alertDiv.classList.add('alert-blue');
    alertDiv.innerHTML = `${message} <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>`;
    document.body.appendChild(alertDiv);

    // Eliminar el mensaje después de 8 segundos
    setTimeout(function () {
        alertDiv.remove();
    }, 8000);
}



document.addEventListener('DOMContentLoaded', function() {
    const fechaActual = new Date().toISOString().split('T')[0];
    

    const fechaPublicInput = document.getElementById('fecha-public');
    fechaPublicInput.setAttribute('max', fechaActual);

    
    fechaPublicInput.addEventListener('blur', function() {
        if(this.value) {
            const fecha = new Date(this.value);
            
        
            if(isNaN(fecha.getTime())) {
                alertBootstrap('Formato de fecha inválido', 'danger');
                this.classList.add('is-invalid');
                return;
            }

          
            if(fecha.getFullYear() < 2019) {
                alertBootstrap('El año no puede ser menor a 2019', 'danger');
                this.classList.add('is-invalid');
                return;
            }

            this.classList.remove('is-invalid');
        }
    });

 
    document.querySelector('form').addEventListener('submit', function(event) {
        const fecha = new Date(fechaPublicInput.value);
        
        if(fecha.getFullYear() < 2019) {
            event.preventDefault();
            alertBootstrap('El año no puede ser menor a 2019', 'danger');
            fechaPublicInput.classList.add('is-invalid');
        }
    });
});
    function validateData() {
       
        let fecha = document.getElementById('fecha-public').value;
        let disabled = false;
    
        
        const hoy = new Date().toISOString().split('T')[0];
        if (fecha > hoy) {
            alertBootstrap('Según Alonso lea el manual Error en fecha', 'danger');
            disabled = true;
            }
    
       
        document.getElementById('fecha-public').addEventListener('blur', function(){
            const today = new Date();
            const diaSeleccionado = new Date(this.value);
            
            // Validación combinada
            if(isNaN(diaSeleccionado.getTime())) {
                alertBootstrap('Formato de fecha inválido', 'warning');
                this.classList.add('is-invalid');
                return;
            }
            
            if(diaSeleccionado.getFullYear() < 2019) {
                alertBootstrap('El año no puede ser menor a 2019', 'danger');
                this.classList.add('is-invalid');
                return;
            }
            
            if(diaSeleccionado > today) {
                alertBootstrap('La fecha no puede ser futura', 'succes');
                this.classList.add('is-invalid');
                return;
            }
            
            this.classList.remove('is-invalid');
            dissableButton();
        });
    
    
        return disabled;
        
    }
    
function dissableButton() {
    let btnSave = document.getElementById('btnSave');
    if (validateData()) {
        btnSave.classList.add('disabled');
    } else {
        btnSave.classList.remove('disabled');
    }

}

document.addEventListener('DOMContentLoaded', function () {
    dissableButton();
});

document.getElementById('titulo-public').addEventListener('input', dissableButton);
document.getElementById('sku-public').addEventListener('input', dissableButton);
document.getElementById('cuenta-public').addEventListener('input', dissableButton);
document.getElementById('fecha-public').addEventListener('input', dissableButton);
document.getElementById('precio-public').addEventListener('input', dissableButton);
document.getElementById('search').addEventListener('input', dissableButton);

document.getElementById('search').addEventListener('input', function () {
    let query = this.value;

    if (query.length > 2) { // Comenzar la búsqueda después de 3 caracteres
        let xhr = new XMLHttpRequest();
        xhr.open('GET', `/productos/searchmodelproduct?query=${query}`, true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                let data = JSON.parse(xhr.responseText);
                let suggestions = document.getElementById('suggestions');
                suggestions.innerHTML = '';

                data.forEach(item => {
                    let li = document.createElement('li');
                    li.textContent = item.modelo;
                    li.classList.add('list-group-item');
                    li.classList.add('hover-sistema-uno');
                    li.style.cursor = "pointer";

                    li.addEventListener('click', function () {
                        document.getElementById('search').value = this.textContent;
                        document.getElementById('hidden-product').value = item.idProducto;
                        suggestions.innerHTML = ''; // Limpiar sugerencias después de seleccionar una
                    });

                    suggestions.appendChild(li);
                });
            }
        };
        xhr.send();
    } else {
        document.getElementById('suggestions').innerHTML = ''; // Limpiar si hay menos de 3 caracteres
    }
});