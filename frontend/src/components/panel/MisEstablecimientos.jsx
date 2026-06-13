import { useState } from 'react'
import { eliminarEstablecimiento } from '../../services/panelService'
import { getPlatos } from '../../services/platoService'
import Modal from '../ui/Modal'
import EstablecimientoForm from './EstablecimientoForm'
import PlatoForm from './PlatoForm'
import FotoUploader from './FotoUploader'

export default function MisEstablecimientos({ establecimientos, onRecargar }) {
    const [modalEditar, setModalEditar]   = useState(false)
    const [modalPlatos, setModalPlatos]   = useState(false)
    const [modalFotos, setModalFotos]     = useState(false)
    const [seleccionado, setSeleccionado] = useState(null)
    const [platos, setPlatos]             = useState([])
    const [fotos, setFotos]               = useState([])

    const abrirEditar = (est) => {
        setSeleccionado(est)
        setModalEditar(true)
    }

    const abrirPlatos = async (est) => {
        setSeleccionado(est)
        const { data } = await getPlatos(est.id)
        setPlatos(data.data || data)
        setModalPlatos(true)
    }

    const abrirFotos = (est) => {
        setSeleccionado(est)
        setFotos(est.fotos || [])
        setModalFotos(true)
    }

    const handleEliminar = async (id) => {
        if (!confirm('¿Desactivar este establecimiento?')) return
        await eliminarEstablecimiento(id)
        onRecargar()
    }

    if (establecimientos.length === 0) {
        return (
            <div className="text-center py-12 text-gray-400">
                <p className="text-4xl mb-3">🏪</p>
                <p>Aún no tienes publicaciones.</p>
            </div>
        )
    }

    return (
        <>
            <div className="space-y-4">
                {establecimientos.map(est => (
                    <div key={est.id}
                        className="bg-white border border-gray-200 rounded-2xl p-5 flex gap-4 items-start">
                        {/* Portada */}
                        <div className="w-20 h-20 rounded-xl overflow-hidden bg-gray-100 shrink-0">
                            {est.portada
                                ? <img src={est.portada.url} alt={est.nombre} className="w-full h-full object-cover" />
                                : <div className="w-full h-full flex items-center justify-center text-2xl">🏪</div>
                            }
                        </div>

                        {/* Info */}
                        <div className="flex-1 min-w-0">
                            <h3 className="font-bold text-gray-800 truncate">{est.nombre}</h3>
                            <p className="text-sm text-gray-500 mt-0.5">{est.ubicacion?.distrito}, {est.ubicacion?.provincia}</p>
                            <div className="flex gap-3 mt-1 text-xs text-gray-400">
                                <span>👁 {est.visitas_count} visitas</span>
                                <span>💬 {est.comentarios_count} comentarios</span>
                            </div>
                            <div className="flex gap-2 mt-3 flex-wrap">
                                <button onClick={() => abrirEditar(est)}
                                    className="text-xs px-3 py-1.5 border border-gray-300 rounded-lg hover:border-green-400 transition">
                                    ✏️ Editar
                                </button>
                                <button onClick={() => abrirPlatos(est)}
                                    className="text-xs px-3 py-1.5 border border-gray-300 rounded-lg hover:border-green-400 transition">
                                    🍽 Platos
                                </button>
                                <button onClick={() => abrirFotos(est)}
                                    className="text-xs px-3 py-1.5 border border-gray-300 rounded-lg hover:border-green-400 transition">
                                    📷 Fotos
                                </button>
                                <button onClick={() => handleEliminar(est.id)}
                                    className="text-xs px-3 py-1.5 border border-red-200 text-red-500 rounded-lg hover:bg-red-50 transition">
                                    🗑 Eliminar
                                </button>
                            </div>
                        </div>
                    </div>
                ))}
            </div>

            {/* Modal editar */}
            <Modal open={modalEditar} onClose={() => setModalEditar(false)} title="Editar establecimiento">
                {seleccionado && (
                    <EstablecimientoForm
                        establecimiento={seleccionado}
                        onGuardado={() => { setModalEditar(false); onRecargar() }}
                        onCancelar={() => setModalEditar(false)}
                    />
                )}
            </Modal>

            {/* Modal platos */}
            <Modal open={modalPlatos} onClose={() => setModalPlatos(false)} title={`Platos — ${seleccionado?.nombre}`}>
                {seleccionado && (
                    <div className="space-y-4">
                        <PlatoForm
                            establecimientoId={seleccionado.id}
                            onGuardado={async () => {
                                const { data } = await getPlatos(seleccionado.id)
                                setPlatos(data.data || data)
                            }}
                            onCancelar={() => {}}
                        />
                        {platos.length > 0 && (
                            <div className="border-t pt-4 space-y-2">
                                {platos.map(p => (
                                    <div key={p.id} className="flex justify-between items-center text-sm">
                                        <span className="font-medium text-gray-700">{p.nombre}</span>
                                        <span className="text-gray-500">S/ {p.precio}</span>
                                    </div>
                                ))}
                            </div>
                        )}
                    </div>
                )}
            </Modal>

            {/* Modal fotos */}
            <Modal open={modalFotos} onClose={() => setModalFotos(false)} title={`Fotos — ${seleccionado?.nombre}`}>
                {seleccionado && (
                    <FotoUploader
                        establecimientoId={seleccionado.id}
                        fotos={fotos}
                        onActualizar={() => {
                            onRecargar()
                            setModalFotos(false)
                        }}
                    />
                )}
            </Modal>
        </>
    )
}