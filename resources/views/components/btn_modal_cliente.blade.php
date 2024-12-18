<button class="btn {{$clases}}" data-bs-toggle="modal" data-bs-target="#nuevoClienteModal"><i
        class="bi bi-person-fill-add"></i> Nuevo Cliente</button>

<form action="{{route('createcliente')}}" method="post">
    @csrf
    <div class="modal fade" id="nuevoClienteModal" tabindex="-1" aria-labelledby="nuevoClienteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="nuevoClienteModalLabel">Nuevo Cliente</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row text-start">
                        <div class="col-12">
                            <label class="form-label client-name">Nombres:</label>
                            <input type="text" name="nombre" placeholder="Persona o Empresa" maxlength="50" class="form-control" required>
                        </div>
                        <div class="col-6 client-apel-patern">
                            <label class="form-label">Apellido Paterno:</label>
                            <input type="text" name="apepaterno" placeholder="Garcia" maxlength="50" class="form-control">
                        </div>
                        <div class="col-6 client-apel-matern">
                            <label class="form-label">Apellido Materno:</label>
                            <input type="text" name="apematerno" placeholder="Tello" maxlength="50" class="form-control">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Documento:</label>
                            <select name="tipodoc" class="form-select" onchange="changeTipeDoc(this)" required>
                                @foreach ($documentos as $doc)
                                <option value="{{$doc->idTipoDocumento}}">{{$doc->descripcion}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label client-document">DNI:</label>
                            <input type="text" name="numerodoc" placeholder="88888888" maxlength="10" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Telefono:</label>
                            <input type="tel" name="numerotelf" placeholder="+51 999 999 999" maxlength="15" class="form-control">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Correo Electronico:</label>
                            <input type="email" name="correo" placeholder="tumail@youmail.com" maxlength="50" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" onclick="return confirm('¿Estas seguro?')"
                        class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    function changeTipeDoc(input) {
        let modalNewClient = document.getElementById('nuevoClienteModal');
        let nombre = modalNewClient.querySelector('.client-name');
        let apePaterno = modalNewClient.querySelector('.client-apel-patern');
        let apeMaterno = modalNewClient.querySelector('.client-apel-matern');
        let documento = modalNewClient.querySelector('.client-document');
        console.log(input.value);

        switch (input.value) {
            case '1':
                apePaterno.style.display = 'block';
                apeMaterno.style.display = 'block';
                documento.textContent = 'DNI:';
                nombre.textContent = 'Nombres:';
                break;
            case '2':
                apePaterno.style.display = 'block';
                apeMaterno.style.display = 'block';
                documento.textContent = 'Carné:';
                nombre.textContent = 'Nombres:';
                break;
            case '3':
                apePaterno.style.display = 'none';
                apeMaterno.style.display = 'none';
                documento.textContent = 'RUC:';
                nombre.textContent = 'Razon Social:';
                break;
            default:
                apePaterno.style.display = 'block';
                apeMaterno.style.display = 'block';
                documento.textContent = 'Nro Documento:';
                nombre.textContent = 'Nombres:';
        }
    }

</script>