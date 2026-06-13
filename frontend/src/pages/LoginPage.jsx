import { useState } from 'react'
import { useNavigate, Link } from 'react-router-dom'
import { login as loginService } from '../services/authService'
import { useAuth } from '../context/AuthContext'

export default function LoginPage() {
    const navigate      = useNavigate()
    const { login }     = useAuth()

    const [form, setForm]       = useState({ email: '', password: '' })
    const [error, setError]     = useState('')
    const [loading, setLoading] = useState(false)

    const handleChange = (e) =>
        setForm({ ...form, [e.target.name]: e.target.value })

    const handleSubmit = async (e) => {
        e.preventDefault()
        setError('')
        setLoading(true)
        try {
            const { data } = await loginService(form)
            login(data.token, data.usuario)

            // MODIFICADO: Redirección inteligente según el rol del usuario
            const usuario = data.usuario;
            if (usuario && (usuario.tipo_usuario === 'empresa' || usuario.role === 'empresa')) {
                // Si es empresa, va a su panel de administración
                navigate('/panel')
            } else {
                // Si es un usuario común (Persona), va directo al Home a explorar
                navigate('/')
            }
            
        } catch (err) {
            setError(err.response?.data?.message || 'Error al iniciar sesión.')
        } finally {
            setLoading(false)
        }
    }

    return (
        <div className="min-h-screen bg-green-50 flex items-center justify-center px-4">
            <div className="bg-white rounded-2xl shadow-md w-full max-w-md p-8">

                <h2 className="text-2xl font-bold text-green-700 mb-1">Iniciar sesión</h2>
                <p className="text-gray-400 text-sm mb-6">Bienvenido de vuelta a RutaSelva</p>

                {error && (
                    <div className="bg-red-50 text-red-600 text-sm rounded-lg px-4 py-3 mb-4">
                        {error}
                    </div>
                )}

                <form onSubmit={handleSubmit} className="flex flex-col gap-4">
                    <div>
                        <label className="text-sm font-medium text-gray-700">Correo electrónico</label>
                        <input
                            type="email"
                            name="email"
                            value={form.email}
                            onChange={handleChange}
                            required
                            placeholder="correo@ejemplo.com"
                            className="mt-1 w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-400"
                        />
                    </div>

                    <div>
                        <label className="text-sm font-medium text-gray-700">Contraseña</label>
                        <input
                            type="password"
                            name="password"
                            value={form.password}
                            onChange={handleChange}
                            required
                            placeholder="••••••"
                            className="mt-1 w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-400"
                        />
                    </div>

                    <button
                        type="submit"
                        disabled={loading}
                        className="w-full py-2.5 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition disabled:opacity-60"
                    >
                        {loading ? 'Ingresando...' : 'Ingresar'}
                    </button>
                </form>

                <p className="text-sm text-center text-gray-500 mt-6">
                    ¿No tienes cuenta?{' '}
                    <Link to="/registro" className="text-green-600 font-medium hover:underline">
                        Regístrate
                    </Link>
                </p>
            </div>
        </div>
    )
}
