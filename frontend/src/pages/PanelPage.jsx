import { useNavigate } from 'react-router-dom'
import { useAuth } from '../context/AuthContext'

export default function PanelPage() {
    const { usuario, logout } = useAuth()
    const navigate            = useNavigate()

    const handleLogout = async () => {
        await logout()
        navigate('/')
    }

    if (!usuario) return null

    const esEmpresa = usuario.tipo === 'empresa'
    const nombre    = esEmpresa
        ? usuario.perfil?.razon_social
        : `${usuario.perfil?.nombre} ${usuario.perfil?.apellido}`

    return (
        <div className="min-h-screen bg-gray-50">
            <header className="bg-green-700 text-white px-6 py-4 flex items-center justify-between">
                <span className="text-xl font-bold">RutaSelva</span>
                <div className="flex items-center gap-4">
                    {/* nombre en lugar de correo */}
                    <span className="text-sm font-medium">{nombre}</span>
                    <button
                        onClick={handleLogout}
                        className="text-sm bg-white text-green-700 px-4 py-1.5 rounded-lg font-semibold hover:bg-green-50 transition"
                    >
                        Cerrar sesión
                    </button>
                </div>
            </header>

            <main className="max-w-3xl mx-auto px-4 py-10">
                <h1 className="text-2xl font-bold text-gray-800 mb-1">
                    Hola, {nombre} 👋
                </h1>
                <p className="text-gray-500 mb-8">
                    {esEmpresa
                        ? 'Panel de empresa — gestiona tus establecimientos.'
                        : 'Panel de usuario — explora y descubre establecimientos.'}
                </p>

                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <FunctionCard
                        title="Explorar establecimientos"
                        desc="Busca y filtra los mejores lugares."
                        onClick={() => navigate('/')}
                        icon="🗺️"
                    />

                    {!esEmpresa && (
                        <>
                            <FunctionCard title="Comentar"  desc="Deja tu opinión en establecimientos." icon="💬" />
                            <FunctionCard title="Calificar" desc="Puntúa con estrellas tus favoritos."   icon="⭐" />
                        </>
                    )}

                    {esEmpresa && (
                        <>
                            <FunctionCard
                                title="Mis publicaciones"
                                desc="Gestiona tus establecimientos publicados."
                                icon="🏪"
                                onClick={() => navigate('/panel/mis-publicaciones')}
                            />
                            <FunctionCard
                                title="Suscripción"
                                desc="Ver o contratar un plan."
                                icon="💳"
                                onClick={() => navigate('/panel/suscripcion')}
                            />
                            <FunctionCard
                                title="Nueva publicación"
                                desc="Publica un nuevo establecimiento."
                                icon="➕"
                                onClick={() => navigate('/panel/mis-publicaciones')}
                            />
                        </>
                    )}
                </div>
            </main>
        </div>
    )
}

function FunctionCard({ title, desc, icon, onClick }) {
    return (
        <button
            onClick={onClick}
            className="text-left bg-white border border-gray-200 rounded-xl p-5 hover:shadow-md hover:border-green-300 transition"
        >
            <div className="text-3xl mb-2">{icon}</div>
            <h3 className="font-semibold text-gray-800">{title}</h3>
            <p className="text-sm text-gray-500 mt-1">{desc}</p>
        </button>
    )
}