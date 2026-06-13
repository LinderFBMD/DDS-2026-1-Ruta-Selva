import { useState, useEffect } from 'react'
import { getMisEstablecimientos } from '../services/panelService'

export function useMisEstablecimientos() {
    const [establecimientos, setEstablecimientos] = useState([])
    const [loading, setLoading]                   = useState(true)
    const [error, setError]                       = useState(null)

    const cargar = () => {
        setLoading(true)
        getMisEstablecimientos()
            .then(({ data }) => setEstablecimientos(data.data))
            .catch(() => setError('Error al cargar establecimientos.'))
            .finally(() => setLoading(false))
    }

    useEffect(() => { cargar() }, [])

    return { establecimientos, loading, error, recargar: cargar }
}