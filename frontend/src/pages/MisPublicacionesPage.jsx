import { useState } from 'react'
import { useNavigate } from 'react-router-dom'
import { useMisEstablecimientos } from '../hooks/useMisEstablecimientos'
import MisEstablecimientos from '../components/panel/MisEstablecimientos'
import Modal from '../components/ui/Modal'
import EstablecimientoForm from '../components/panel/EstablecimientoForm'

export default function MisPublicacionesPage() {
    const navigate = useNavigate()
    const { establecimientos, loading, error, recargar } = useMisEstablecimientos()
    const [modalNuevo, setModalNuevo] = useState(false)

    return (
        <div className="min-h-screen bg-gray-50">
            <div className="max-w-3xl mx-auto px-4 py-10">
                <button onClick={() => navigate('/panel')}
                    className="text-sm text-green-700 font-medium mb-6 flex items-center gap-1 hover:underline">
                    ← Volver al panel
                </button>

                <div className="flex items-center justify-between mb-6">
                    <h1 className="text-2xl font-bold text-gray-800">Mis publicaciones</h1>
                    <button
                        onClick={() => setModalNuevo(true)}
                        className="bg-green-600 text-white px-4 py-2 rounded-xl font-semibold hover:bg-green-700 transition text-sm">
                        + Nueva publicación
                    </button>
                </div>

                {loading && (
                    <div className="text-center py-12 text-gray-400">Cargando...</div>
                )}

                {!loading && error && (
                    <div className="text-center py-12 text-gray-400">
                        <p className="text-4xl mb-3">🏪</p>
                        <p>Aún no tienes publicaciones.</p>
                    </div>
                )}

                {!loading && !error && (
                    <MisEstablecimientos
                        establecimientos={establecimientos}
                        onRecargar={recargar}
                    />
                )}

                <Modal open={modalNuevo} onClose={() => setModalNuevo(false)} title="Nueva publicación">
                    <EstablecimientoForm
                        onGuardado={() => { setModalNuevo(false); recargar() }}
                        onCancelar={() => setModalNuevo(false)}
                    />
                </Modal>
            </div>
        </div>
    )
}