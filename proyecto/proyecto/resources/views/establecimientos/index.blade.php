@extends('layouts.app')
@section('title', 'Quintas — Turismo Quillabamba')

@section('content')

    <h1 class="pagina-titulo">Quintas y lugares turísticos</h1>
    <p class="pagina-sub">Explora los mejores establecimientos de Quillabamba</p>

    @if($establecimientos->isEmpty())
        <div class="vacio">
            <span class="icono">🌿</span>
            <p>No hay establecimientos registrados aún.</p>
        </div>
    @else
        <div class="lista-quintas">
            @foreach($establecimientos as $est)
                <div class="fila-quinta">

                    {{-- FOTO IZQUIERDA --}}
                    <div class="fila-foto">
                        @if($est->portada && $est->portada->url)
                            
                            <img src="{{ $est->portada->url_completa }}" alt="{{ $est->nombre }}">
                        @else
                            🏡
                        @endif
                    </div>

                    {{-- INFO DERECHA --}}
                    <div class="fila-info">
                        <a class="fila-nombre" href="{{ route('establecimientos.show', $est) }}">
                            {{ $est->nombre ?? 'Sin nombre' }}
                        </a>

                        @if($est->descripcion)
                            <p class="fila-desc">{{ $est->descripcion }}</p>
                        @endif

                        <div class="fila-meta">
                            @if($est->precio_entrada)
                                <span>💰 S/ {{ number_format($est->precio_entrada, 2) }}</span>
                            @endif
                            @if($est->horario_apertura && $est->horario_cierre)
                                <span>🕐 {{ \Carbon\Carbon::parse($est->horario_apertura)->format('H:i') }} – {{ \Carbon\Carbon::parse($est->horario_cierre)->format('H:i') }}</span>
                            @endif
                            @if($est->tiene_internet)
                                <span class="badge-wifi">📶 WiFi</span>
                            @endif
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

        <div class="paginacion">
            {{ $establecimientos->links() }}
        </div>
    @endif

@endsection