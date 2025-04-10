let arrayCodes = [];
const messageDuplicity = document.getElementById('duplicity-code');
const checkLenguaje = document.getElementById('check-lenguaje-scan');



//---------------------------------------------------------------SCANNER---------------------------------------------------------------------

  let rowResponse = document.getElementById('scan-row-response');
  let serial = '';
  let timeoutId; 


document.getElementById('barcode-input').addEventListener('input', function(e) {
    let barcode = e.target.value; // Obtiene el código escaneado
    
    if (barcode) {
        if(!checkLenguaje.checked){
            barcode = changeCharEngToEs(barcode);
        }
      
      serial += barcode;

      e.target.value = '';

      clearTimeout(timeoutId);

      timeoutId = setTimeout(() => {
        addModalCodesScan(serial);
        serial = '';
      }, 100);
    }
    
  });

  function addModalCodesScan(serie){
    if (arrayCodes.some(element => element == serie)) {
        messageDuplicity.textContent = 'Codigo repetido';
        setTimeout(() => { messageDuplicity.textContent = ''; }, 1000);
    } else {
        let colSerial = document.createElement('div');
        colSerial.classList.add('col-6','mb-2');
        let rowSerial = document.createElement('div');
        rowSerial.classList.add('row','ms-1','me-1','text-center','border','rounded-3');
        rowSerial.innerHTML = '<small>' + serie + '</small>';
        colSerial.appendChild(rowSerial);
        rowResponse.appendChild(colSerial);
        arrayCodes.push(serie);
    }
  }

  function addModalCodesCamera() {
    let code = hiddenResultadosCamera.value;

    if (code != '' && code != null) {
        if (arrayCodes.some(element => element == code)) {
            messageError.textContent = 'Codigo repetido';
            messageError.classList.add('text-danger');
            setTimeout(() => { 
                messageError.textContent = ''; 
                messageError.classList.remove('text-danger');
            }, 500);
        } else {
            messageError.textContent = 'Codigo agregado';
            messageError.classList.add('text-success');
            arrayCodes.push(code);
            setTimeout(() => { 
                messageError.textContent = ''; 
                messageError.classList.remove('text-success');
            }, 500);
        }
    }
}

  function clearListScan(){
    rowResponse.innerHTML = '';
    arrayCodes = [];
  }

//---------------------------------------------------------------GENERALES-------------------------------------------------------------------
function listCodesModal() {
    let ulCodes = document.getElementById('list-codes-saved');

    stopVideo();
    if (arrayCodes.length > 0) {
        ulCodes.innerHTML = '';
        arrayCodes.forEach(function (x, index) {
            let itemCode = document.createElement('li');
            itemCode.classList.add('list-group-item');

            let divRow = document.createElement('div');
            divRow.classList.add('row');

            let divColSerie = document.createElement('div');
            divColSerie.classList.add('col-9', 'text-start');
            divColSerie.textContent = x;

            let divColBtnDelete = document.createElement('div');
            divColBtnDelete.classList.add('col-3', 'text-end');
            let btnDelete = document.createElement('button');
            btnDelete.classList.add('btn', 'btn-danger', 'btn-sm');
            btnDelete.innerHTML = '<i class="bi bi-x-lg"></i>';
            btnDelete.addEventListener('click', function (event) {
                arrayCodes.splice(index, 1);
                itemCode.remove();
            });
            divColBtnDelete.appendChild(btnDelete);

            divRow.appendChild(divColSerie);
            divRow.appendChild(divColBtnDelete);
            itemCode.appendChild(divRow);
            ulCodes.appendChild(itemCode);
        });
    } else {
        ulCodes.innerHTML = '<h5 class="text-center text-secondary">Sin codigos</h5>';
    }
}


function getSerials() {
    return arrayCodes;
}

document.getElementById('btn-modal-camera-uno').addEventListener('click',function(event){
    listCodesModal();
});

document.getElementById('btn-modal-camera-dos').addEventListener('click',function(event){
    addModalCodesCamera();
});