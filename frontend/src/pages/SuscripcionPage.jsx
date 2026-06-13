import { useSuscripcion } from '../hooks/useSuscripcion'
import SuscripcionCard from '../components/panel/SuscripcionCard'
import { useNavigate } from 'react-router-dom'

export default function SuscripcionPage() {
    const navigate = useNavigate()
    const { suscripcion, planes, loading, error, setSuscripcion } = useSuscripcion()

    if (loading) return <div className="flex items-center justify-center min-h-screen text-gray-400">Cargando...</div>
    if (error)   return <div className="flex items-center justify-center min-h-screen text-red-500">{error}</div>

    return (
        <div className="min-h-screen bg-gray-50">
            <div className="max-w-2xl mx-auto px-4 py-10">
                <button onClick={() => navigate('/panel')}
                    className="text-sm text-green-700 font-medium mb-6 flex items-center gap-1 hover:underline">
                    ← Volver al panel
                </button>
                <h1 className="text-2xl font-bold text-gray-800 mb-6">Suscripción</h1>
                <SuscripcionCard
                    suscripcion={suscripcion}
                    planes={planes}
                    onActualizar={() => window.location.reload()}
                />
            </div>
        </div>
    )
}