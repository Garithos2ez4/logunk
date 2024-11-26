let isDisabledCalGeneral = false;
    let isDisabledCorreos = false;

    function sendIdToModalPlataforma(hidden,text,id,title){
        let inputHidden = document.getElementById(hidden);
        let inputText = document.getElementById(text);

        inputHidden.value = id;
        inputText.textContent = title;
    }

    function modalComision(titulo,grupo,categoria){
        let tituloComision = document.getElementById('comisionModalLabel');
        let divComision = document.getElementById('div-comision-' + grupo);
        let listComisiones = divComision.querySelectorAll('.hidden-comision');
        let hiddenGroup = document.getElementById('comisionHiddenGrup');
        let hiddenCategory = document.getElementById('categoryModalComision');
        
        
        listComisiones.forEach(function(x){
            let liComision = document.getElementById('comisionModalList-' + x.dataset.rango);
            
            liComision.value = x.value;
        });
        
        hiddenGroup.value = grupo;
        hiddenCategory.value = categoria;
        
        tituloComision.textContent = 'Comisiones: ' + titulo;
    }

    function viewElementsComision(category){
        @foreach($categorias as $categoria)
            if(category == {{$categoria->idCategoria}}){
                let divCategory  = document.querySelectorAll('.divCategory-' + {{$categoria->idCategoria}});
                divCategory.forEach(function(x){
                    x.style.display = 'block';
                });
            }else{
                let divCategory  = document.querySelectorAll('.divCategory-' + {{$categoria->idCategoria}});
                divCategory.forEach(function(x){
                    x.style.display = 'none';
                });
            }
        @endforeach
    }

    function calculateGeneralDisabled() {
        let btnSaveCalculos = document.getElementById('btnSaveCalculos');
        let divCalculosGenerales = document.getElementById('calculos-generales');
        let btnEditCalcGeneral = divCalculosGenerales.querySelectorAll('.input-edit'); 
    
        isDisabledCalGeneral = !isDisabledCalGeneral;
        
        if(isDisabledCalGeneral){
            btnSaveCalculos.style.display = 'none';
        }else{
            btnSaveCalculos.style.display = 'inline-flex';
        }
    
        btnEditCalcGeneral.forEach(function(x) {
            x.disabled = isDisabledCalGeneral;
        });
    }

    function validateModalComision(){
        let divModal = document.getElementById('createPlataformaModal');
        let btnModal = document.getElementById('modal-comisionxplataforma-btn');
        let inputs = divModal.querySelectorAll('input');
        let disabledBtn = false;

        inputs.forEach(function(x){
            if(x.value == ''){
                disabledBtn = true;
            }
        });
        
        btnModal.disabled = disabledBtn;
    }

    document.addEventListener('DOMContentLoaded', function() {
        calculateGeneralDisabled();
        let oldCategoryComision = document.getElementById('categoryModalComision').value;
        viewElementsComision(oldCategoryComision);
        validateModalComision();
    });