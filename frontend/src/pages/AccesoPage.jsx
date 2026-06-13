import { useNavigate } from 'react-router-dom'

export default function AccesoPage() {
    const navigate = useNavigate()

    return (
        <div className="min-h-screen bg-green-50 flex flex-col items-center justify-center px-4">
            <h1 className="text-4xl font-bold text-green-700 mb-2">RutaSelva</h1>
            <p className="text-gray-500 mb-10">¿Qué deseas hacer?</p>

            <div className="flex flex-col gap-4 w-full max-w-xs">
                <button
                    onClick={() => navigate('/login')}
                    className="w-full py-3 rounded-xl bg-green-600 text-white font-semibold text-lg hover:bg-green-700 transition"
                >
                    Iniciar sesión
                </button>
                <button
                    onClick={() => navigate('/registro')}
                    className="w-full py-3 rounded-xl border-2 border-green-600 text-green-600 font-semibold text-lg hover:bg-green-100 transition"
                >
                    Crear cuenta
                </button>
            </div>

            <button
                onClick={() => navigate('/')}
                className="mt-8 text-sm text-gray-400 hover:text-gray-600"
            >
                ← Volver al inicio
            </button>
        </div>
    )
}