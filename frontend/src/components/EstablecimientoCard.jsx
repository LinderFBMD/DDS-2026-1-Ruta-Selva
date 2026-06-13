import { useNavigate } from 'react-router-dom'

export default function EstablecimientoCard({ establecimiento }) {
    const navigate = useNavigate()

    const {
        id, nombre, descripcion, portada,
        categorias, tipo, promedio_estrellas,
        total_comentarios, ubicacion, horario_apertura, horario_cierre
    } = establecimiento

    // Evaluación en tiempo real para el badge estilo TripAdvisor
    const evaluarDisponibilidad = () => {
        if (!horario_apertura || !horario_cierre) return { texto: 'Sin horario', css: 'bg-gray-100 text-gray-600' };
        
        const ahora = new Date();
        const horaActual = ahora.getHours() * 60 + ahora.getMinutes();
        
        const [ha, ma] = horario_apertura.split(':').map(Number);
        const [hc, mc] = horario_cierre.split(':').map(Number);
        
        const minutosApertura = ha * 60 + ma;
        const minutosCierre = hc * 60 + mc;
        
        let abierto = false;
        if (minutosApertura <= minutosCierre) {
            abierto = horaActual >= minutosApertura && horaActual <= minutosCierre;
        } else {
            abierto = horaActual >= minutosApertura || horaActual <= minutosCierre;
        }

        return abierto 
            ? { texto: 'Abierto ahora', css: 'bg-green-100 text-green-800 font-semibold' }
            : { texto: 'Cerrado', css: 'bg-red-100 text-red-800 font-semibold' };
    };

    const estadoAtencion = evaluarDisponibilidad();

    return (
        <div
            onClick={() => navigate(`/establecimiento/${id}`)}
            className="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden cursor-pointer hover:shadow-md hover:border-green-200 transition flex flex-col sm:flex-row w-full"
        >
            {/* Foto de portada a la izquierda */}
            <div className="w-full sm:w-64 h-48 sm:h-44 bg-gray-100 shrink-0">
                {portada ? (
                    <img
                        src={portada}
                        alt={nombre}
                        className="w-full h-full object-cover"
                    />
                ) : (
                    <div className="w-full h-full flex items-center justify-center text-gray-300 text-4xl">
                        🍽️
                    </div>
                )}
            </div>

            {/* Contenido a la derecha */}
            <div className="p-4 flex flex-col justify-between flex-1 min-w-0">
                <div>
                    <div className="flex justify-between items-start gap-2">
                        <h3 className="font-bold text-gray-800 text-lg truncate">{nombre}</h3>
                        <span className={`text-xs px-2.5 py-1 rounded-full whitespace-nowrap ${estadoAtencion.css}`}>
                            {estadoAtencion.texto}
                        </span>
                    </div>

                    {/* Categorías y Tipo */}
                    <div className="flex flex-wrap items-center gap-1.5 mt-1.5">
                        {tipo?.nombre && (
                            <span className="bg-gray-100 text-gray-700 text-xs px-2 py-0.5 rounded-md font-medium">
                                {tipo.nombre}
                            </span>
                        )}
                        {categorias?.map((c, i) => (
                            <span
                                key={i}
                                className="bg-green-50 text-green-700 text-xs px-2 py-0.5 rounded-full border border-green-100"
                            >
                                {typeof c === 'object' ? c.nombre : c}
                            </span>
                        ))}
                    </div>

                    {/* Descripción */}
                    <p className="text-sm text-gray-500 mt-2 line-clamp-2 leading-relaxed">{descripcion}</p>
                </div>

                {/* Footer de la Card */}
                <div className="flex items-center justify-between mt-3 pt-2 border-t border-gray-50">
                    <div className="flex items-center gap-1.5 text-sm">
                        <span className="text-yellow-400 text-base">★</span>
                        <span className="font-bold text-gray-700">
                            {promedio_estrellas > 0 ? Number(promedio_estrellas).toFixed(1) : '—'}
                        </span>
                        <span className="text-gray-400 text-xs">
                            ({total_comentarios || 0} opiniones)
                        </span>
                    </div>
                    {ubicacion && (
                        <p className="text-sm text-gray-500">
                            📍 {establecimiento.ubicacion?.distrito}, {establecimiento.ubicacion?.departamento}
                        </p>
                    )}
                </div>
            </div>
        </div>
    )
}