import { useState, useEffect } from 'react'
import { useParams, useNavigate } from 'react-router-dom'
import { getEstablecimiento, storeComentario } from '../services/establecimientoService'
import { useAuth } from '../context/AuthContext'

export default function EstablecimientoPage() {
    const { id }                = useParams()
    const navigate              = useNavigate()
    const { usuario }           = useAuth()
    const [estab, setEstab]     = useState(null)
    const [loading, setLoading] = useState(true)
    const [fotoAmpliada, setFotoAmpliada] = useState(null)
    const [fotoIdx, setFotoIdx] = useState(0)

    // CORREGIDO: Usamos 'texto' para coincidir con la base de datos de Laravel
    const [texto, setTexto] = useState('')
    const [estrellas, setEstrellas] = useState(0)
    const [hoverEstrellas, setHoverEstrellas] = useState(0)
    const [enviando, setEnviando] = useState(false)
    const [errorMsg, setErrorMsg] = useState('')

    useEffect(() => {
        getEstablecimiento(id)
            .then(({ data }) => setEstab(data.data ?? data))
            .catch(() => navigate('/'))
            .finally(() => setLoading(false))
    }, [id])

    useEffect(() => {
        const handleKey = (e) => {
            if (!fotoAmpliada) return
            if (e.key === 'Escape') setFotoAmpliada(null)
            if (e.key === 'ArrowRight') irFoto(1)
            if (e.key === 'ArrowLeft')  irFoto(-1)
        }
        window.addEventListener('keydown', handleKey)
        return () => window.removeEventListener('keydown', handleKey)
    }, [fotoAmpliada, fotoIdx])

    if (loading) return (
        <div className="flex items-center justify-center min-h-screen text-gray-400">
            Cargando...
        </div>
    )

    if (!estab) return null

    const {
        nombre, descripcion, tipo, categorias, fotos, platos,
        tiene_internet, horario_apertura, horario_cierre,
        promedio_estrellas, total_comentarios, ubicacion, lista_comentarios
    } = estab

    const todasFotos = fotos || []
    const portada    = todasFotos.find(f => f.es_portada) || todasFotos[0]
    const resto      = todasFotos.filter(f => f !== portada)

    const urlMaps = ubicacion?.latitud && ubicacion?.longitud
        ? `https://maps.google.com/?q=${ubicacion.latitud},${ubicacion.longitud}`
        : null

    const abrirFoto = (url, idx) => {
        setFotoAmpliada(url)
        setFotoIdx(idx)
    }

    const irFoto = (dir) => {
        const nuevoIdx = (fotoIdx + dir + todasFotos.length) % todasFotos.length
        setFotoIdx(nuevoIdx)
        setFotoAmpliada(todasFotos[nuevoIdx].url)
    }

    // Comprobar si este usuario ya dejó un comentario previo
    const yaComento = lista_comentarios?.some(c => c.autor === (usuario?.perfil?.nombre || usuario?.name))

    // Manejar el envío de la valoración
    const handleSubmitComentario = async (e) => {
        e.preventDefault()
        setErrorMsg('')

        if (estrellas === 0) {
            setErrorMsg('Por favor, selecciona una calificación haciendo clic en las estrellas.')
            return
        }

        setEnviando(true)
        try {
            // CORREGIDO: Enviamos 'texto' al servicio (el servicio ya lo mapea bien al backend)
            const response = await storeComentario(id, texto, estrellas)
            setEstab(response.data.data) // Actualizamos dinámicamente el estado local
            setTexto('')
            setEstrellas(0)
        } catch (err) {
            setErrorMsg(err.response?.data?.message || 'Ocurrió un error al enviar tu comentario.')
        } finally {
            setEnviando(false)
        }
    }

    return (
        <div className="min-h-screen bg-gray-50">

            {/* Lightbox */}
            {fotoAmpliada && (
                <div
                    className="fixed inset-0 z-50 flex items-center justify-center bg-black/85"
                    onClick={() => setFotoAmpliada(null)}
                >
                    <button
                        onClick={() => setFotoAmpliada(null)}
                        className="absolute top-4 right-4 w-10 h-10 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/25 text-white text-lg transition border border-white/20 z-10"
                    >
                        ✕
                    </button>

                    {todasFotos.length > 1 && (
                        <>
                            <button
                                onClick={(e) => { e.stopPropagation(); irFoto(-1) }}
                                className="absolute left-4 w-10 h-10 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/25 text-white text-xl transition border border-white/20"
                            >
                                ‹
                            </button>
                            <button
                                onClick={(e) => { e.stopPropagation(); irFoto(1) }}
                                className="absolute right-16 w-10 h-10 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/25 text-white text-xl transition border border-white/20"
                            >
                                ›
                            </button>
                        </>
                    )}

                    <div
                        className="max-w-4xl max-h-[90vh] mx-16"
                        onClick={e => e.stopPropagation()}
                    >
                        <img
                            src={fotoAmpliada}
                            alt=""
                            className="max-w-full max-h-[90vh] object-contain rounded-xl shadow-2xl"
                        />
                        <p className="text-white/50 text-xs text-center mt-2">
                            {fotoIdx + 1} / {todasFotos.length}
                        </p>
                    </div>
                </div>
            )}

            {/* Navbar */}
            <header className="bg-white shadow-sm px-6 py-4 flex items-center justify-between">
                <button onClick={() => navigate('/')} className="text-green-700 font-bold text-xl hover:opacity-80 transition">
                    ← RutaSelva
                </button>
                {!usuario ? (
                    <button onClick={() => navigate('/acceso')}
                        className="text-sm bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                        Ingresar
                    </button>
                ) : (
                    <button onClick={() => navigate('/panel')}
                        className="text-sm bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                        Mi panel
                    </button>
                )}
            </header>

            {/* Fotos */}
            <div className="max-w-6xl mx-auto px-4 mt-6">
                {todasFotos.length === 0 ? (
                    <div className="h-80 bg-gray-200 flex items-center justify-center text-6xl text-gray-300 rounded-xl">
                        🍽️
                    </div>
                ) : todasFotos.length === 1 ? (
                    <div
                        className="h-80 bg-gray-900 overflow-hidden cursor-zoom-in rounded-xl shadow-sm"
                        onClick={() => abrirFoto(todasFotos[0].url, 0)}
                    >
                        <img src={todasFotos[0].url} alt={nombre} className="w-full h-full object-cover" />
                    </div>
                ) : (
                    <div className="flex h-80 rounded-xl overflow-hidden shadow-sm gap-1 bg-gray-200">
                        <div
                            className="w-2/3 overflow-hidden cursor-zoom-in"
                            onClick={() => abrirFoto(portada.url, todasFotos.indexOf(portada))}
                        >
                            <img src={portada.url} alt={nombre} className="w-full h-full object-cover hover:scale-102 transition-transform duration-300" />
                        </div>

                        <div className="w-1/3 grid grid-cols-1 gap-1">
                            {resto.slice(0, 3).map((f, i) => {
                                const idx = todasFotos.indexOf(f)
                                const esUltima = i === 2 && todasFotos.length > 4
                                return (
                                    <div
                                        key={i}
                                        className="overflow-hidden cursor-zoom-in relative"
                                        onClick={() => abrirFoto(f.url, idx)}
                                    >
                                        <img src={f.url} alt="" className="w-full h-full object-cover hover:scale-105 transition-transform duration-300" />
                                        {esUltima && (
                                            <div className="absolute inset-0 bg-black/55 flex items-center justify-center backdrop-blur-xs">
                                                <span className="text-white font-bold text-base">
                                                    +{todasFotos.length - 4} fotos
                                                </span>
                                            </div>
                                        )}
                                    </div>
                                )
                            })}
                        </div>
                    </div>
                )}
            </div>

            <main className="max-w-3xl mx-auto px-4 py-8">

                {/* Encabezado */}
                <div className="flex items-start justify-between gap-4 mb-4">
                    <div>
                        <h1 className="text-2xl font-bold text-gray-800">{nombre}</h1>
                        {tipo && <span className="text-sm text-gray-400">{tipo}</span>}
                    </div>
                    <div className="text-center shrink-0">
                        <div className="text-3xl font-bold text-yellow-500 flex items-center gap-1 justify-center">
                            ★ <span className="text-gray-800">{promedio_estrellas > 0 ? promedio_estrellas : '—'}</span>
                        </div>
                        <div className="text-xs text-gray-400">{total_comentarios} reseñas</div>
                    </div>
                </div>

                {/* Categorías */}
                {categorias?.length > 0 && (
                    <div className="flex flex-wrap gap-2 mb-4">
                        {categorias.map((c, i) => (
                            <span key={i} className="bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full">
                                {c}
                            </span>
                        ))}
                    </div>
                )}

                {/* Descripción */}
                {descripcion && <p className="text-gray-600 mb-6">{descripcion}</p>}

                {/* Info rápida */}
                <div className="bg-white rounded-xl border border-gray-100 p-4 mb-4 grid grid-cols-2 gap-3 text-sm">
                    <div>
                        <span className="text-gray-400">Horario</span>
                        <p className="font-medium text-gray-700">
                            {horario_apertura && horario_cierre ? `${horario_apertura} - ${horario_cierre}` : 'No especificado'}
                        </p>
                    </div>
                    <div>
                        <span className="text-gray-400">WiFi</span>
                        <p className="font-medium text-gray-700">
                            {tiene_internet ? '✅ Disponible' : '❌ No disponible'}
                        </p>
                    </div>
                    {ubicacion?.direccion && (
                        <div className="col-span-2">
                            <span className="text-gray-400">Dirección</span>
                            <p className="font-medium text-gray-700">{ubicacion.direccion}</p>
                        </div>
                    )}
                </div>

                {/* Botón Cómo llegar */}
                {urlMaps && (
                    <a href={urlMaps} target="_blank" rel="noopener noreferrer"
                        className="flex items-center justify-center gap-2 w-full py-3 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 transition mb-6">
                        🗺️ Cómo llegar — Google Maps
                    </a>
                )}

                {/* Platos */}
                {platos?.length > 0 && (
                    <div className="mb-6">
                        <h2 className="font-semibold text-gray-700 mb-3">🍽️ Menú</h2>
                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            {platos.map((p) => (
                                <div key={p.id} className="bg-white border border-gray-100 rounded-xl overflow-hidden flex gap-3 p-3">
                                    {p.foto_url ? (
                                        <img src={p.foto_url} alt={p.nombre} className="w-16 h-16 object-cover rounded-lg shrink-0 cursor-zoom-in" onClick={() => setFotoAmpliada(p.foto_url)} />
                                    ) : (
                                        <div className="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center text-2xl shrink-0">🍴</div>
                                    )}
                                    <div className="flex-1 min-w-0">
                                        <p className="font-semibold text-gray-800 text-sm truncate">{p.nombre}</p>
                                        {p.descripcion && <p className="text-xs text-gray-500 mt-0.5 line-clamp-2">{p.descripcion}</p>}
                                        {p.precio && <p className="text-sm font-bold text-green-700 mt-1">S/ {p.precio}</p>}
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                )}

                {/* ==================== PARTE 4: SECCIÓN COMENTARIOS Y CALIFICACIONES ==================== */}
                <div className="bg-white rounded-xl border border-gray-100 p-6 shadow-sm">
                    <h2 className="font-bold text-lg text-gray-800 mb-6">
                        Comentarios y Opiniones ({total_comentarios})
                    </h2>

                    {/* Formulario para publicar opinión */}
                    <div className="mb-8 border-b border-gray-100 pb-6">
                        {!usuario ? (
                            <div className="text-center py-4 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                                <p className="text-gray-500 text-sm mb-3">Inicia sesión como persona para comentar y calificar</p>
                                <button onClick={() => navigate('/acceso')} className="px-5 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 transition">
                                    Iniciar sesión
                                </button>
                            </div>
                        ) : usuario.tipo !== 'persona' ? (
                            <div className="p-4 bg-amber-50 text-amber-800 text-sm rounded-xl border border-amber-200">
                                ⚠️ Solo las cuentas de tipo <strong>Persona</strong> pueden dejar calificaciones. Las cuentas de tipo Empresa no tienen permitido comentar.
                            </div>
                        ) : yaComento ? (
                            <div className="p-4 bg-blue-50 text-blue-800 text-sm rounded-xl border border-blue-200">
                                ✓ Ya has compartido tu opinión sobre este establecimiento. ¡Muchas gracias por tu colaboración!
                            </div>
                        ) : (
                            <form onSubmit={handleSubmitComentario} className="space-y-4">
                                <h3 className="text-sm font-semibold text-gray-700">Cuéntanos tu experiencia:</h3>
                                
                                {/* Estrellas Interactivas Clicables */}
                                <div className="flex items-center gap-1">
                                    {[1, 2, 3, 4, 5].map((num) => (
                                        <button
                                            key={num}
                                            type="button"
                                            className="text-3xl focus:outline-none transition-transform duration-100 hover:scale-110"
                                            onClick={() => setEstrellas(num)}
                                            onMouseEnter={() => setHoverEstrellas(num)}
                                            onMouseLeave={() => setHoverEstrellas(0)}
                                        >
                                            <span className={(num <= (hoverEstrellas || estrellas)) ? 'text-yellow-400' : 'text-gray-200'}>
                                                ★
                                            </span>
                                        </button>
                                    ))}
                                    <span className="text-xs text-gray-400 ml-2 font-medium">
                                        ({estrellas} de 5 estrellas)
                                    </span>
                                </div>

                                {/* CORREGIDO: Usamos la variable 'texto' correctamente */}
                                <textarea
                                    value={texto}
                                    onChange={(e) => setTexto(e.target.value)}
                                    placeholder="Escribe tu reseña aquí (mínimo 5 caracteres)..."
                                    rows="3"
                                    required
                                    className="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                                />

                                {errorMsg && <p className="text-xs text-red-500 bg-red-50 p-2.5 rounded-lg font-medium">{errorMsg}</p>}

                                <div className="flex justify-end">
                                    <button
                                        type="submit"
                                        disabled={enviando}
                                        className="px-6 py-2 bg-green-600 text-white rounded-xl text-sm font-semibold hover:bg-green-700 transition disabled:opacity-50"
                                    >
                                        {enviando ? 'Publicando...' : 'Publicar comentario'}
                                    </button>
                                </div>
                            </form>
                        )}
                    </div>

                    {/* Feed de Comentarios (Estilo Red Social) */}
                    <div className="space-y-4">
                        {!lista_comentarios || lista_comentarios.length === 0 ? (
                            <p className="text-center text-sm text-gray-400 py-6">
                                Aún no hay opiniones. ¡Sé el primero en dejar una!
                            </p>
                        ) : (
                            lista_comentarios.map((c) => (
                                <div key={c.id} className="p-4 bg-gray-50 rounded-xl border border-gray-100 flex flex-col gap-1.5 shadow-xs">
                                    <div className="flex items-center justify-between">
                                        {/* Nombre del autor estilo Red Social */}
                                        <span className="font-bold text-sm text-gray-800 tracking-tight">
                                            @{c.autor}
                                        </span>
                                        {/* Estrellas estáticas del comentario */}
                                        <div className="text-yellow-400 text-sm">
                                            {'★'.repeat(c.estrellas)}{'☆'.repeat(5 - c.estrellas)}
                                        </div>
                                    </div>
                                    <p className="text-sm text-gray-600 font-normal leading-relaxed whitespace-pre-wrap">
                                        {c.texto}
                                    </p>
                                </div>
                            ))
                        )}
                    </div>
                </div>

            </main>
        </div>
    )
}
