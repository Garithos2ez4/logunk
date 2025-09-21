@extends('layouts.app')

@section('title', 'Observaciones')

@section('content')
<div class="container">
    <br>
    <div class="row">
        <div class="col-12">
            <h2>Productos en Observaciones <i class="bi bi-award"></i></h2>
        </div>

        <!-- Filtro por mes/año -->
        <form action="{{ route('observaciones') }}" method="GET">
            <div class="input-group mb-3">
                <input 
                    type="month"    
                    name="date" 
                    class="form-control" 
                    value="{{ request('date') ?? now()->format('Y-m') }}"
                >
                <button class="btn btn-primary" type="submit">Filtrar</button>
            </div>
        </form>

        @if($observaciones->isNotEmpty())
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Observación</th>
                        <th>Fecha Garantía</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($observaciones as $observacion)
                        <tr>
                            <td>{{ $observacion->idRegistro }}</td>
                            <td>{{ $observacion->observacion }}</td>
                            <td>{{ \Carbon\Carbon::parse($observacion->fechaMovimiento)->format('d/m/Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Paginación -->
            <div class="d-flex justify-content-center">
                {{ $observaciones->links() }}
            </div>
        @else
            <p>No hay observaciones disponibles.</p>
        @endif
    </div>
</div>
@endsection
