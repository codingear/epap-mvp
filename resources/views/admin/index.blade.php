@extends('admin.layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Dashboard</h4>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5 class="card-title">Usuarios</h5>
                        <p class="card-text display-4">150</p>
                        <a href="#" class="btn btn-light btn-sm">Ver detalles</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5 class="card-title">Ventas</h5>
                        <p class="card-text display-4">$9,500</p>
                        <a href="#" class="btn btn-light btn-sm">Ver detalles</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <h5 class="card-title">Productos</h5>
                        <p class="card-text display-4">75</p>
                        <a href="#" class="btn btn-light btn-sm">Ver detalles</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Actividad Reciente
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Nueva venta registrada
                                <span class="badge bg-primary rounded-pill">Ahora</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Usuario registrado
                                <span class="badge bg-primary rounded-pill">2h</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Nuevo producto añadido
                                <span class="badge bg-primary rounded-pill">3h</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Acciones Rápidas
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary" type="button">Añadir Usuario</button>
                            <button class="btn btn-success" type="button">Registrar Venta</button>
                            <button class="btn btn-info" type="button">Nuevo Producto</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        transition: transform .3s;
    }
    .card:hover {
        transform: translateY(-5px);
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Dashboard cargado correctamente');
    });
</script>
@endpush