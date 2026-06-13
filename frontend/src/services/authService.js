import axios from 'axios'

const API = import.meta.env.VITE_API_URL || 'http://localhost:8000/api'

const api = axios.create({ baseURL: API })

// Adjunta el token JWT a cada request si existe
api.interceptors.request.use((config) => {
    const token = localStorage.getItem('token')
    if (token) config.headers.Authorization = `Bearer ${token}`
    return config
})

export const registerPersona = (data) =>
    api.post('/auth/register/persona', data)

export const registerEmpresa = (data) =>
    api.post('/auth/register/empresa', data)

export const login = (data) =>
    api.post('/auth/login', data)

export const logout = () =>
    api.post('/auth/logout')

export const getMe = () =>
    api.get('/auth/me')

export default api