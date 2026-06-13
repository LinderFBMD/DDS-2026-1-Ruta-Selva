import api from './authService'

export const getMisEstablecimientos = () =>
    api.get('/panel/mis-establecimientos')

export const getMiSuscripcion = () =>
    api.get('/panel/suscripcion')

export const crearEstablecimiento = (data) =>
    api.post('/panel/establecimientos', data)

export const actualizarEstablecimiento = (id, data) =>
    api.put(`/panel/establecimientos/${id}`, data)

export const eliminarEstablecimiento = (id) =>
    api.delete(`/panel/establecimientos/${id}`)