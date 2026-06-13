import { useState, useEffect } from 'react'
import { getMiSuscripcion, getPlanes } from '../services/suscripcionService'

export function useSuscripcion() {
    const [suscripcion, setSuscripcion] = useState(null)
    const [planes, setPlanes]           = useState([])
    const [loading, setLoading]         = useState(true)
    const [error, setError]             = useState(null)

    useEffect(() => {
        Promise.all([getMiSuscripcion(), getPlanes()])
            .then(([resSus, resPlanes]) => {
                setSuscripcion(resSus.data.suscripcion)
                setPlanes(resPlanes.data.data)
            })
            .catch(() => setError('Error al cargar suscripción.'))
            .finally(() => setLoading(false))
    }, [])

    return { suscripcion, planes, loading, error, setSuscripcion }
}