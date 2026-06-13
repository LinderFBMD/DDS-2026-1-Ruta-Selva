import api from './authService'

export const getPlatos = (establecimientoId) =>
    api.get(`/establecimientos/${establecimientoId}/platos`)

export const crearPlato = (establecimientoId, data) =>
    api.post(`/establecimientos/${establecimientoId}/platos`, data)

export const actualizarPlato = (establecimientoId, platoId, data) =>
    api.put(`/establecimientos/${establecimientoId}/platos/${platoId}`, data)

export const eliminarPlato = (establecimientoId, platoId) =>
    api.delete(`/establecimientos/${establecimientoId}/platos/${platoId}`)

export const agregarFoto = (establecimientoId, data) =>
    api.post(`/establecimientos/${establecimientoId}/fotos`, data)

export const eliminarFoto = (establecimientoId, fotoId) =>
    api.delete(`/establecimientos/${establecimientoId}/fotos/${fotoId}`)

export const setPortada = (establecimientoId, fotoId) =>
    api.patch(`/establecimientos/${establecimientoId}/fotos/${fotoId}/portada`)
