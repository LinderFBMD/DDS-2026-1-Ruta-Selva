@extends('layouts.app')
@section('title', ($establecimiento->nombre ?? 'Detalle') . ' — Turismo Quillabamba')

@section('content')

    <a class="btn-volver" href="{{ route('establecimientos.index') }}">← Volver al listado</a>

    <h1 class="det-nombre">{{ $establecimiento->nombre ?? 'Sin nombre' }}</h1>

    <div class="det-badges">
        @if($establecimiento->precio_entrada)
            <span class="det-badge">💰 S/ {{ number_format($establecimiento->precio_entrada, 2) }} entrada</span>
        @endif
        @if($establecimiento->horario_apertura && $establecimiento->horario_cierre)
            <span class="det-badge">
                🕐 {{ \Carbon\Carbon::parse($establecimiento->horario_apertura)->format('H:i') }}
                –
                {{ \Carbon\Carbon::parse($establecimiento->horario_cierre)->format('H:i') }}
            </span>
        @endif
        @if($establecimiento->tiene_internet)
            <span class="det-badge wifi">📶 WiFi disponible</span>
        @endif
    </div>

    {{-- FOTOS EN GRID --}}
    <p class="seccion-titulo">Fotos</p>
    <div class="fotos-grid">
        @if($establecimiento->fotos->isNotEmpty())
            @foreach($establecimiento->fotos as $foto)
                <div class="foto-item">
                    @if($foto->url)
                        <img src="{{ $foto->url_completa }}" alt="Foto de {{ $establecimiento->nombre }}">
                    @else
                        🌿
                    @endif
                </div>
            @endforeach
        @else
            {{-- Emojis de placeholder cuando no hay fotos --}}
            <div class="foto-item">🏡</div>
            <div class="foto-item">🌿</div>
            <div class="foto-item">🌄</div>
            <div class="foto-item">🌳</div>
        @endif
    </div>

    {{-- DESCRIPCIÓN --}}
    @if($establecimiento->descripcion)
        <p class="seccion-titulo">Descripción</p>
        <p class="descripcion">{{ $establecimiento->descripcion }}</p>
    @endif

    {{-- DATOS --}}
    <p class="seccion-titulo">Información</p>
    <div class="datos-grid">
        @if($establecimiento->precio_entrada)
            <div class="dato-item">
                <span class="dato-label">💰 Precio entrada</span>
                <span class="dato-valor">S/ {{ number_format($establecimiento->precio_entrada, 2) }}</span>
            </div>
        @endif
        @if($establecimiento->horario_apertura)
            <div class="dato-item">
                <span class="dato-label">🕐 Apertura</span>
                <span class="dato-valor">{{ \Carbon\Carbon::parse($establecimiento->horario_apertura)->format('H:i') }}</span>
            </div>
        @endif
        @if($establecimiento->horario_cierre)
            <div class="dato-item">
                <span class="dato-label">🕔 Cierre</span>
                <span class="dato-valor">{{ \Carbon\Carbon::parse($establecimiento->horario_cierre)->format('H:i') }}</span>
            </div>
        @endif
        <div class="dato-item">
            <span class="dato-label">📶 Internet</span>
            <span class="dato-valor">{{ $establecimiento->tiene_internet ? 'Disponible' : 'No disponible' }}</span>
        </div>
    </div>

@endsection