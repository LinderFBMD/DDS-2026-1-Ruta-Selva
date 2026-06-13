import { useState } from 'react'
import { suscribirse, pagarSuscripcion } from '../../services/suscripcionService'
import Modal from '../ui/Modal'

export default function SuscripcionCard({ suscripcion, planes, onActualizar }) {
    const [modalPlan, setModalPlan]       = useState(false)
    const [modalPago, setModalPago]       = useState(false)
    const [planSeleccionado, setPlan]     = useState(null)
    const [metodoPago, setMetodoPago]     = useState('yape')
    const [loading, setLoading]           = useState(false)
    const [nuevaSus, setNuevaSus]         = useState(null)

    const handleSuscribirse = async () => {
        if (!planSeleccionado) return
        setLoading(true)
        try {
            const { data } = await suscribirse(planSeleccionado.id)
            setNuevaSus(data.suscripcion)
            setModalPlan(false)
            setModalPago(true)
        } catch {
            alert('Error al crear suscripción.')
        } finally {
            setLoading(false)
        }
    }

    const handlePagar = async () => {
        if (!nuevaSus) return
        setLoading(true)
        try {
            await pagarSuscripcion({
                suscripcion_id: nuevaSus.id,
                metodo_pago:    metodoPago,
            })
            setModalPago(false)
            onActualizar()
            alert('¡Pago registrado! Tu suscripción está activa.')
        } catch {
            alert('Error al registrar el pago.')
        } finally {
            setLoading(false)
        }
    }

    return (
        <div className="bg-white border border-gray-200 rounded-2xl p-6">
            <h2 className="text-lg font-bold text-gray-800 mb-4">Mi Suscripción</h2>

            {suscripcion ? (
                <div className="space-y-2">
                    <p className="text-green-700 font-semibold text-lg">{suscripcion.plan.nombre}</p>
                    <p className="text-sm text-gray-500">
                        Válido hasta: <span className="font-medium text-gray-700">{suscripcion.fecha_fin}</span>
                    </p>
                    <p className="text-sm text-gray-500">
                        Hasta <span className="font-medium text-gray-700">{suscripcion.plan.max_establecimientos}</span> publicaciones
                    </p>
                    <span className="inline-block mt-2 px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                        Activa
                    </span>
                </div>
            ) : (
                <div>
                    <p className="text-gray-500 text-sm mb-4">No tienes un plan activo. Elige uno para publicar.</p>
                </div>
            )}

            <button
                onClick={() => setModalPlan(true)}
                className="mt-4 w-full bg-green-600 text-white py-2 rounded-xl font-semibold hover:bg-green-700 transition"
            >
                {suscripcion ? 'Cambiar plan' : 'Contratar plan'}
            </button>

            {/* Modal selección de plan */}
            <Modal open={modalPlan} onClose={() => setModalPlan(false)} title="Elige tu plan">
                <div className="space-y-3">
                    {planes.map(plan => (
                        <button
                            key={plan.id}
                            onClick={() => setPlan(plan)}
                            className={`w-full text-left border rounded-xl p-4 transition ${
                                planSeleccionado?.id === plan.id
                                    ? 'border-green-500 bg-green-50'
                                    : 'border-gray-200 hover:border-green-300'
                            }`}
                        >
                            <div className="flex justify-between items-center">
                                <span className="font-semibold text-gray-800">{plan.nombre}</span>
                                <span className="text-green-700 font-bold">S/ {plan.precio}</span>
                            </div>
                            <p className="text-xs text-gray-500 mt-1">{plan.descripcion}</p>
                        </button>
                    ))}
                </div>
                <button
                    onClick={handleSuscribirse}
                    disabled={!planSeleccionado || loading}
                    className="mt-4 w-full bg-green-600 text-white py-2 rounded-xl font-semibold hover:bg-green-700 disabled:opacity-50 transition"
                >
                    {loading ? 'Procesando...' : 'Continuar al pago'}
                </button>
            </Modal>

            {/* Modal pago simulado */}
            <Modal open={modalPago} onClose={() => setModalPago(false)} title="Simular pago">
                <p className="text-sm text-gray-500 mb-4">
                    Monto a pagar: <span className="font-bold text-gray-800">S/ {planSeleccionado?.precio}</span>
                </p>
                <label className="block text-sm font-medium text-gray-700 mb-2">Método de pago</label>
                <div className="grid grid-cols-2 gap-2 mb-4">
                    {['yape', 'plin', 'tarjeta', 'transferencia'].map(m => (
                        <button
                            key={m}
                            onClick={() => setMetodoPago(m)}
                            className={`py-2 rounded-xl border font-medium capitalize transition ${
                                metodoPago === m
                                    ? 'border-green-500 bg-green-50 text-green-700'
                                    : 'border-gray-200 text-gray-600 hover:border-green-300'
                            }`}
                        >
                            {m}
                        </button>
                    ))}
                </div>
                <button
                    onClick={handlePagar}
                    disabled={loading}
                    className="w-full bg-green-600 text-white py-2 rounded-xl font-semibold hover:bg-green-700 disabled:opacity-50 transition"
                >
                    {loading ? 'Procesando...' : 'Confirmar pago'}
                </button>
            </Modal>
        </div>
    )
}