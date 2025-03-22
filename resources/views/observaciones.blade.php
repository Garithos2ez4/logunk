@extends('layouts.app')

@section('title', 'Observaciones')

@section('content')
<div class="container">
    <br>
    <div class="row">
        <div class="col-12">
            <h2>Productos en Observaci&oacute;nes <i class="bi bi-award"></i></h2>
        </div>

        <div class="col-12">
            <form action="{{ route('observaciones') }}" method="GET">
                <div class="input-group">
                    <input type="month" name="date" class="form-control" value="{{ request('date') }}" />
                    <button class="btn btn-primary" type="submit">Filtrar</button>
                </div>
            </form>
        </div>

        @if($observaciones->isNotEmpty())
            <table class="table table bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Observación</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($observaciones as $observacion)
                        <tr>
                            <td>{{ $observacion->idRegistro }}</td>
                            <td>{{ $observacion->observacion }}</td>
                            <td>{{ $observacion->fechaGarantia }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Paginación -->
            {{ $observaciones->links() }}
        @else
            <p>No hay observaciones disponibles.</p>
        @endif
    </div>
</div>
@endsection
