import { useState, useEffect } from 'react'
import { useNavigate } from 'react-router-dom'
import { useAuth } from '../context/AuthContext'
import { getEstablecimientos } from '../services/establecimientoService'
import EstablecimientoCard from '../components/EstablecimientoCard'
import Buscador from '../components/Buscador'
import FiltrosLaterales from '../components/FiltrosLaterales'
import LogoApp from '../assets/LogoApp.png'

export default function HomePage() {
    const navigate        = useNavigate()
    const { usuario }     = useAuth()
    const [establecimientos, setEstablecimientos] = useState([])
    const [orden, setOrden]   = useState('valorados') 
    const [loading, setLoading] = useState(true)
    const [pagina, setPagina]   = useState(1)
    const [hayMas, setHayMas]   = useState(false)

    // Estados de filtros laterales
    const [depSeleccionado, setDepSeleccionado] = useState('')
    const [provSeleccionada, setProvSeleccionada] = useState('')
    const [categoriaFiltro, setCategoriaFiltro] = useState('')
    const [valoracionMinima, setValoracionMinima] = useState(0)
    const [soloAbiertos, setSoloAbiertos] = useState(false)

    // Nuevo estado para el buscador híbrido
    const [textoBuscar, setTextoBuscar] = useState('')

    // Agregamos 'textoBuscar' a las dependencias para que filtre al presionar Enter o limpiar
    useEffect(() => {
        cargar(1, orden, depSeleccionado, provSeleccionada, categoriaFiltro, textoBuscar)
    }, [orden, depSeleccionado, provSeleccionada, categoriaFiltro, textoBuscar])

    // Recibe el parámetro 'buscar' e implementa la consulta segura a la API
    const cargar = async (pag, ord, dep, prov, cat, buscar = '') => {
        setLoading(true)
        try {
            const params = { 
                orden: ord, 
                page: pag,
                ...(dep && { departamento: dep }),
                ...(prov && { provincia: prov }),
                ...(cat && { categoria: cat }),
                ...(buscar && { q: buscar })
            }
            
            const { data } = await getEstablecimientos(params)
            
            if (pag === 1) {
                setEstablecimientos(data.data || [])
            } else {
                setEstablecimientos(prev => [...prev, ...(data.data || [])])
            }
            setHayMas(!!data.next_page_url)
            setPagina(pag)
        } catch (_) {
        } finally {
            setLoading(false)
        }
    }

    const handleDepartamentoChange = (dep) => {
        setDepSeleccionado(dep)
        setProvSeleccionada('') 
    }

    // Filtrado local en Frontend
    const establecimientosFiltradosFront = (establecimientos || []).filter(e => {
        if (valoracionMinima > 0 && (e.promedio_estrellas || 0) < valoracionMinima) {
            return false;
        }
        if (soloAbiertos) {
            if (!e.horario_apertura || !e.horario_cierre) return false;
            const ahora = new Date();
            const horaActual = ahora.getHours() * 60 + ahora.getMinutes();
            const [ha, ma] = e.horario_apertura.split(':').map(Number);
            const [hc, mc] = e.horario_cierre.split(':').map(Number);
            const minutosApertura = ha * 60 + ma;
            const minutosCierre = hc * 60 + mc;
            if (minutosApertura <= minutosCierre) {
                return horaActual >= minutosApertura && horaActual <= minutosCierre;
            } else {
                return horaActual >= minutosApertura || horaActual <= minutosCierre;
            }
        }
        return true;
    });

    return (
        <div className="min-h-screen bg-gray-50">
            {/* Navbar */}
            <header className="bg-white shadow-sm px-6 py-4 flex items-center justify-between">
                <div className="flex items-center gap-3">
                    <img 
                        src={LogoApp} 
                        alt="Logo RutaSelva" 
                        className="h-9 w-auto object-contain drop-shadow-sm" 
                    />
                    <span className="text-xl font-bold text-green-700 tracking-tight">RutaSelva</span>
                </div>
                
                {usuario ? (
                    <div className="flex items-center gap-3">
                        <span className="text-sm text-gray-600 hidden sm:block">
                            {usuario.tipo === 'empresa'
                                ? usuario.perfil?.razon_social
                                : usuario.perfil?.nombre}
                        </span>
                        <button
                            onClick={() => navigate('/panel')}
                            className="text-sm bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition"
                        >
                            Mi panel
                        </button>
                    </div>
                ) : (
                    <button
                        onClick={() => navigate('/acceso')}
                        className="text-sm bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition"
                    >
                        Ingresar
                    </button>
                )}
            </header>

            {/* Hero */}
            <div className="bg-green-700 px-6 py-12 flex flex-col items-center text-center">
                <h1 className="text-3xl font-bold text-white mb-2">
                    Descubre los mejores lugares
                </h1>
                <p className="text-green-200 mb-6">
                    Restaurantes, cafeterías, comida típica y más
                </p>
                
                <Buscador onBuscarSubmit={setTextoBuscar} valorInicial={textoBuscar} />
            </div>

            {/* Layout Dividido */}
            <main className="max-w-7xl mx-auto px-4 py-8">
                <div className="flex flex-col md:flex-row gap-8">
                    
                    {/* Sidebar de Filtros Único */}
                    <aside className="w-full md:w-64 shrink-0">
                        <FiltrosLaterales 
                            ordenActual={orden}
                            onOrdenChange={setOrden}
                            depSeleccionado={depSeleccionado}
                            onDepChange={handleDepartamentoChange}
                            provSeleccionada={provSeleccionada}
                            onProvChange={setProvSeleccionada}
                            categoriaFiltro={categoriaFiltro}
                            onCategoriaChange={setCategoriaFiltro}
                            valoracionMinima={valoracionMinima}
                            onValoracionChange={setValoracionMinima}
                            soloAbiertos={soloAbiertos}
                            onSoloAbiertosChange={setSoloAbiertos}
                        />
                    </aside>

                    {/* Feed de Resultados */}
                    <div className="flex-1">
                        <div className="mb-6 flex justify-end items-center">
                            <span className="text-sm text-gray-500 font-medium">
                                {establecimientosFiltradosFront.length} resultados encontrados
                            </span>
                        </div>

                        {loading && pagina === 1 ? (
                            <div className="text-center text-gray-400 py-20">Cargando...</div>
                        ) : establecimientosFiltradosFront.length === 0 ? (
                            <div className="text-center text-gray-400 py-20 bg-white rounded-2xl border border-gray-100 shadow-sm">
                                No se encontraron establecimientos con esos filtros.
                            </div>
                        ) : (
                            <>
                                <div className="flex flex-col gap-4">
                                    {establecimientosFiltradosFront.map((e) => (
                                        <div key={e.id}>
                                            {/* ¡Arreglado aquí! Ahora usa EstablecimientoCard en español */}
                                            <EstablecimientoCard establecimiento={e} />
                                        </div>
                                    ))}
                                </div>

                                {hayMas && (
                                    <div className="text-center mt-8">
                                        <button
                                            onClick={() => cargar(pagina + 1, orden, depSeleccionado, provSeleccionada, categoriaFiltro, textoBuscar)}
                                            disabled={loading}
                                            className="px-6 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition disabled:opacity-60"
                                        >
                                            {loading ? 'Cargando...' : 'Ver más'}
                                        </button>
                                    </div>
                                )}
                            </>
                        )}
                    </div>

                </div>
            </main>
        </div>
    )
}

