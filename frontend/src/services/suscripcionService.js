import api from './authService'

export const getPlanes = () =>
    api.get('/suscripciones/planes')

export const suscribirse = (planId) =>
    api.post('/suscripciones', { plan_id: planId })

export const pagarSuscripcion = (data) =>
    api.post('/pagos', data)

export const getMiSuscripcion = () =>
    api.get('/panel/suscripcion')