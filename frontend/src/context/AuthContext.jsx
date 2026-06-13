import { createContext, useContext, useState, useEffect } from 'react'
import { getMe, logout as logoutService } from '../services/authService'

const AuthContext = createContext(null)

export function AuthProvider({ children }) {
    const [usuario, setUsuario]   = useState(null)
    const [loading, setLoading]   = useState(true)

    // Al montar: si hay token en localStorage, recupera el usuario
    useEffect(() => {
        const token = localStorage.getItem('token')
        if (!token) {
            setLoading(false)
            return
        }
        getMe()
            .then(({ data }) => setUsuario(data.usuario))
            .catch(() => localStorage.removeItem('token'))
            .finally(() => setLoading(false))
    }, [])

    const login = (token, usuarioData) => {
        localStorage.setItem('token', token)
        setUsuario(usuarioData)
    }

    const logout = async () => {
        try { await logoutService() } catch (_) {}
        localStorage.removeItem('token')
        setUsuario(null)
    }

    return (
        <AuthContext.Provider value={{ usuario, loading, login, logout }}>
            {children}
        </AuthContext.Provider>
    )
}

export function useAuth() {
    return useContext(AuthContext)
}