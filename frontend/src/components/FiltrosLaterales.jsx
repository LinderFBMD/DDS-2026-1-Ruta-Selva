import { useState, useEffect } from 'react';
import axios from 'axios'; 

export default function FiltrosLaterales({
    categoriaFiltro, onCategoriaChange,
    valoracionMinima, onValoracionChange,
    soloAbiertos, onSoloAbiertosChange
}) {
    const [categorias, setCategorias] = useState([]);
    
    // Secciones colapsables activas (Ubicación eliminada por completo)
    const [openCategorias, setOpenCategorias] = useState(true);
    const [openValoracion, setOpenValoracion] = useState(true);
    const [openEstado, setOpenEstado] = useState(true);

    useEffect(() => {
        // Cargar Categorías apuntando directo al puerto de Laravel
        axios.get('http://127.0.0.1:8000/api/categorias')
            .then((response) => {
                const resData = response.data;

                if (!resData) {
                    setCategorias([]);
                    return;
                }

                // Extraemos la información si viene envuelta en .data o directa
                let datosLimpios = resData.data ? resData.data : resData;

                // SI ES UN ARRAY: Lo guardamos directo
                if (Array.isArray(datosLimpios)) {
                    setCategorias(datosLimpios);
                } 
                // SI ES UN OBJETO ASOCIATIVO: Extraemos sus valores para forzar un Array plano
                else if (typeof datosLimpios === 'object') {
                    setCategorias(Object.values(datosLimpios));
                } 
                else {
                    setCategorias([]);
                }
            })
            .catch(() => setCategorias([]));
    }, []);

    const categoriasParaMapear = Array.isArray(categorias) ? categorias : [];

    return (
        <div className="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col gap-5 sticky top-4">
            <h2 className="font-bold text-gray-800 text-lg border-b pb-2">Filtros</h2>

            {/* Categorías */}
            <div>
                <button 
                    type="button"
                    onClick={() => setOpenCategorias(!openCategorias)}
                    className="w-full flex justify-between items-center font-semibold text-gray-700 text-sm mb-2 hover:text-green-600"
                >
                    <span>🍽️ Categorías</span>
                    <span>{openCategorias ? '▲' : '▼'}</span>
                </button>

                {openCategorias && (
                    <div className="flex flex-col gap-2 mt-2 max-h-56 overflow-y-auto pl-1 pr-1 scrollbar-thin">
                        <label className="flex items-center gap-2 text-sm text-gray-600 cursor-pointer py-0.5">
                            <input
                                type="radio"
                                name="categoria"
                                checked={categoriaFiltro === ''}
                                onChange={() => onCategoriaChange('')}
                                className="text-green-600 focus:ring-green-500 h-4 w-4 border-gray-300"
                            />
                            <span className="font-medium text-gray-700">Todas las opciones</span>
                        </label>
                        {categoriasParaMapear.map((cat) => (
                            <label key={cat.id || cat.nombre} className="flex items-center gap-2 text-sm text-gray-600 cursor-pointer py-0.5">
                                <input
                                    type="radio"
                                    name="categoria"
                                    checked={categoriaFiltro === cat.nombre}
                                    onChange={() => onCategoriaChange(cat.nombre)}
                                    className="text-green-600 focus:ring-green-500 h-4 w-4 border-gray-300"
                                />
                                <span>{cat.nombre}</span>
                            </label>
                        ))}
                    </div>
                )}
            </div>

            <hr className="border-gray-100" />

            {/* Puntuación */}
            <div>
                <button 
                    type="button"
                    onClick={() => setOpenValoracion(!openValoracion)}
                    className="w-full flex justify-between items-center font-semibold text-gray-700 text-sm mb-2 hover:text-green-600"
                >
                    <span>⭐ Puntuación</span>
                    <span>{openValoracion ? '▲' : '▼'}</span>
                </button>

                {openValoracion && (
                    <div className="flex flex-col gap-2 mt-2 pl-1">
                        {[5, 4, 3].map((estrellas) => (
                            <label key={estrellas} className="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                                <input
                                    type="radio"
                                    name="valoracion"
                                    checked={valoracionMinima === estrellas}
                                    onChange={() => onValoracionChange(estrellas)}
                                    className="text-green-600 focus:ring-green-500 h-4 w-4 border-gray-300"
                                />
                                <span className="flex items-center gap-0.5 font-medium text-gray-700">
                                    {estrellas} <span className="text-yellow-400">★</span> o más
                                </span>
                            </label>
                        ))}
                        <label className="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                            <input
                                type="radio"
                                name="valoracion"
                                checked={valoracionMinima === 0}
                                onChange={() => onValoracionChange(0)}
                                className="text-green-600 focus:ring-green-500 h-4 w-4 border-gray-300"
                            />
                            <span>Cualquier puntuación</span>
                        </label>
                    </div>
                )}
            </div>

            <hr className="border-gray-100" />

            {/* Disponibilidad */}
            <div>
                <button 
                    type="button"
                    onClick={() => setOpenEstado(!openEstado)}
                    className="w-full flex justify-between items-center font-semibold text-gray-700 text-sm mb-2 hover:text-green-600"
                >
                    <span>🕒 Disponibilidad</span>
                    <span>{openEstado ? '▲' : '▼'}</span>
                </button>

                {openEstado && (
                    <div className="mt-2 pl-1">
                        <label className="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                            <input
                                type="checkbox"
                                checked={soloAbiertos}
                                onChange={(e) => onSoloAbiertosChange(e.target.checked)}
                                className="rounded text-green-600 focus:ring-green-500 h-4 w-4 border-gray-300"
                            />
                            <span className="font-medium text-gray-700">Abierto ahora</span>
                        </label>
                    </div>
                )}
            </div>
        </div>
    );
}