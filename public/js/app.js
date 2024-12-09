const toolbarOptions = [
    ['bold', 'italic', 'strike'],
    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
    [{ 'size': ['small', false, 'large', 'huge'] }],
    [{ 'color': [] }, { 'background': [] }],
    [{ 'align': [] }],
  
    ['clean']                                         // remove formatting button
  ];
const quill = new Quill('#text-bandeja', {
    modules: {
        toolbar: toolbarOptions
      },
    theme: 'snow'
});

document.getElementById('form-update-bandeja').addEventListener('submit', function (event) {
    event.preventDefault();

    let form = document.getElementById('form-update-bandeja');
    let bandejaInput = document.getElementById('bandeja-input');
    bandejaInput.value = quill.root.innerHTML;
    let formData = new FormData(form);


    let loader = document.getElementById('modalBandeja-total-body');
    loader.style.display = 'flex';

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'Accept': 'application/json',
        },
    })
        .then(response => response.json())
        .then(data => {
            loader.style.display = 'none';
        })
        .catch(error => {
            loader.style.display = 'none';
            console.log('Error: ' + error);
        });
});

var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
})

function hiddeInputDate(id) {
    let inputDate = document.getElementById(id);
    inputDate.click();

}

function createLi(clases,id){
    let liElement = document.createElement('li');
    liElement.id = id;
    if(clases && clases.length > 0){
        clases.forEach(function(x){
            liElement.classList.add(x);
        });
    }
    return liElement;
}

function createInput(clases,id,type,value,name){
    let inputElement = document.createElement('input');
    if(id != null){
        inputElement.id = id;
    }

    if(name != null){
        inputElement.name = name;
    }
    
    if(clases && clases.length > 0){
        clases.forEach(function(x){
            inputElement.classList.add(x);
        });
    }

    inputElement.type = type;
    inputElement.value = value;
    return inputElement;
}

function createDiv(clases,id){
    let divElement = document.createElement('div');
    if(id != null){
        divElement.id = id;
    }   
    
    if(clases && clases.length > 0){
        clases.forEach(function(x){
            divElement.classList.add(x);
        });
    }

    return divElement;
}

function createH5(clases,id,html){
    let h5Element = document.createElement('h5');
    if(id != null){
        h5Element.id = id;
    }   
    
    if(clases && clases.length > 0){
        clases.forEach(function(x){
            h5Element.classList.add(x);
        });
    }

    h5Element.innerHTML = html;
    return h5Element;
}

function createParrafo(clases,id,html){
    let parrafElement = document.createElement('p');
    if(id != null){
        parrafElement.id = id;
    }   
    
    if(clases && clases.length > 0){
        clases.forEach(function(x){
            parrafElement.classList.add(x);
        });
    }

    parrafElement.innerHTML = html;
    return parrafElement;
}

function createButton(clases,id,html,type,metodos){
    let buttonElement = document.createElement('button');
    if(id != null){
        buttonElement.id = id;
    }   
    
    if(clases && clases.length > 0){
        clases.forEach(function(x){
            buttonElement.classList.add(x);
        });
    }

    buttonElement.innerHTML = html;
    buttonElement.type = type;
    if(metodos && metodos.length > 0){
        metodos.forEach(function(x){
            buttonElement.addEventListener('click', x);
        });
    }
    
    return buttonElement;
}

