// Volvemos a importar desde tu archivo real de servicios que contiene la instancia configurada
import api from './authService'; 

// Obtener todos los establecimientos (con filtros opcionales)
export const getEstablecimientos = (params) =>
    api.get('/establecimientos', { params });

// Buscar establecimientos por texto/query
export const buscarEstablecimientos = (q) =>
    api.get('/establecimientos/buscar', { params: { q } });

// Obtener los detalles de un establecimiento específico por su ID
export const getEstablecimiento = (id) =>
    api.get(`/establecimientos/${id}`);

// NUEVO: Guardar un comentario/reseña en un establecimiento usando la misma instancia funcional
export const storeComentario = (establecimientoId, texto, estrellas) => {
    return api.post('/establecimientos/comentarios', {
        establecimiento_id: establecimientoId,
        texto,
        estrellas
    });
};