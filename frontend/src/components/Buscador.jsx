import { useState, useEffect, useRef } from "react";
import { useNavigate } from "react-router-dom";
import axios from "axios";

export default function Buscador({ onBuscarSubmit, valorInicial = "" }) {
  const [query, setQuery] = useState(valorInicial);
  const [sugerencias, setSugerencias] = useState([]);
  const [abierto, setAbierto] = useState(false);
  const [indexSeleccionado, setIndexSeleccionado] = useState(-1);
  
  const navigate = useNavigate();
  const timeoutRef = useRef(null);
  const buscadorRef = useRef(null);

  // Sincronizar si cambia desde afuera
  useEffect(() => {
    setQuery(valorInicial);
  }, [valorInicial]);

  useEffect(() => {
    if (query.trim().length < 2) {
      setSugerencias([]);
      setAbierto(false);
      setIndexSeleccionado(-1);
      return;
    }

    clearTimeout(timeoutRef.current);
    timeoutRef.current = setTimeout(async () => {
      try {
        const response = await axios.get(`/api/establecimientos/buscar`, {
          params: { q: query } 
        });
        const resData = response.data;
        const listaSugerencias = resData.data ? resData.data : resData;
        
        if (Array.isArray(listaSugerencias)) {
          setSugerencias(listaSugerencias);
          setAbierto(listaSugerencias.length > 0);
        } else {
          setSugerencias([]);
          setAbierto(false);
        }
      } catch (_) {
        setSugerencias([]);
        setAbierto(false);
      }
      setIndexSeleccionado(-1);
    }, 300);

    return () => clearTimeout(timeoutRef.current);
  }, [query]);

  useEffect(() => {
    const clickFuera = (e) => {
      if (buscadorRef.current && !buscadorRef.current.contains(e.target)) {
        setAbierto(false);
      }
    };
    document.addEventListener("mousedown", clickFuera);
    return () => document.removeEventListener("mousedown", clickFuera);
  }, []);

  // Al dar Enter o Clic en la lupa, ejecuta la búsqueda general en la página
  const ejecutarBusquedaGeneral = (e) => {
    if (e) e.preventDefault();
    setAbierto(false);
    if (onBuscarSubmit) {
      onBuscarSubmit(query); // Envía el texto al componente padre (HomePage)
    }
  };

  const seleccionarSugerencia = (id) => {
    setQuery("");
    setAbierto(false);
    navigate(`/establecimiento/${id}`);
  };

  const manejarTeclado = (e) => {
    if (!abierto || sugerencias.length === 0) {
      // Si la persiana está cerrada y presiona Enter, busca de forma general
      if (e.key === "Enter") {
        ejecutarBusquedaGeneral(e);
      }
      return;
    }

    if (e.key === "ArrowDown") {
      e.preventDefault();
      setIndexSeleccionado((prev) => (prev < sugerencias.length - 1 ? prev + 1 : 0));
    } else if (e.key === "ArrowUp") {
      e.preventDefault();
      setIndexSeleccionado((prev) => (prev > 0 ? prev - 1 : sugerencias.length - 1));
    } else if (e.key === "Enter") {
      e.preventDefault();
      if (indexSeleccionado >= 0 && indexSeleccionado < sugerencias.length) {
        seleccionarSugerencia(sugerencias[indexSeleccionado].id);
      } else {
        ejecutarBusquedaGeneral(e);
      }
    } else if (e.key === "Escape") {
      setAbierto(false);
    }
  };

  return (
    <div ref={buscadorRef} className="relative w-full max-w-lg">
      <form onSubmit={ejecutarBusquedaGeneral} className="bg-white rounded-xl shadow-sm border border-gray-200 flex items-center px-4 py-3 gap-2 focus-within:border-green-500 focus-within:ring-1 focus-within:ring-green-500 transition-all">
        <button type="submit" className="text-gray-400 hover:text-green-600 text-lg transition-colors">
          🔍
        </button>
        <input
          type="text"
          value={query}
          onChange={(e) => setQuery(e.target.value)}
          onKeyDown={manejarTeclado}
          placeholder="Busca un restaurante, cafetería..."
          className="flex-1 text-sm outline-none text-gray-700 bg-transparent"
        />
        {query && (
          <button
            type="button"
            onClick={() => {
              setQuery("");
              setAbierto(false);
              if (onBuscarSubmit) onBuscarSubmit("");
            }}
            className="text-gray-400 hover:text-gray-600 text-lg font-bold"
          >
            ×
          </button>
        )}
      </form>

      {/* Sugerencias desplegables */}
      {abierto && sugerencias.length > 0 && (
        <ul className="absolute z-50 w-full bg-white border border-gray-200 rounded-xl mt-1 shadow-lg overflow-hidden max-h-64 overflow-y-auto">
          {sugerencias.map((s, index) => (
            <li
              key={s.id}
              onClick={() => seleccionarSugerencia(s.id)}
              onMouseEnter={() => setIndexSeleccionado(index)}
              className={`px-4 py-3 text-sm text-gray-700 cursor-pointer border-b border-gray-50 last:border-none transition-colors flex items-center justify-between ${
                index === indexSeleccionado ? "bg-green-50 text-green-800 font-medium" : ""
              }`}
            >
              <span>{s.nombre}</span>
              <span className="text-xs text-gray-400 font-normal">Ver perfil →</span>
            </li>
          ))}
        </ul>
      )}
    </div>
  );
}