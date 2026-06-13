import { useState, useEffect } from 'react'
import { crearEstablecimiento, actualizarEstablecimiento } from '../../services/panelService'
import api from '../../services/authService'

const INICIAL = {
    nombre: '', descripcion: '', tipo_id: '',
    tiene_internet: false,
    horario_apertura: '', horario_cierre: '',
    categorias: [],
    departamento: 'San Martín', provincia: '', distrito: '',
    direccion: '', referencia: '', latitud: '', longitud: '',
}

export default function EstablecimientoForm({ establecimiento = null, onGuardado, onCancelar }) {
    const [form, setForm]             = useState(establecimiento ? mapearForm(establecimiento) : INICIAL)
    const [tipos, setTipos]           = useState([])
    const [cats, setCats]             = useState([])
    const [loading, setLoading]       = useState(false)
    const [geoLoading, setGeoLoading] = useState(false)
    const [error, setError]           = useState(null)

    useEffect(() => {
        Promise.all([
            api.get('/tipos-establecimiento'),
            api.get('/categorias'),
        ]).then(([t, c]) => {
            setTipos(t.data)
            setCats(c.data)
        })
    }, [])

    const handleChange = (e) => {
        const { name, value, type, checked } = e.target
        setForm(prev => ({ ...prev, [name]: type === 'checkbox' ? checked : value }))
    }

    const toggleCategoria = (id) => {
        setForm(prev => ({
            ...prev,
            categorias: prev.categorias.includes(id)
                ? prev.categorias.filter(c => c !== id)
                : [...prev.categorias, id],
        }))
    }

    const handleGeolocalizacion = () => {
        if (!navigator.geolocation) {
            alert('Tu navegador no soporta geolocalización.')
            return
        }
        setGeoLoading(true)
        navigator.geolocation.getCurrentPosition(
            async (pos) => {
                const lat = pos.coords.latitude
                const lng = pos.coords.longitude

                setForm(prev => ({
                    ...prev,
                    latitud:  lat.toFixed(8),
                    longitud: lng.toFixed(8),
                }))

                try {
                    const res  = await fetch(
                        `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json&accept-language=es`
                    )
                    const data = await res.json()
                    const addr = data.address || {}

                    setForm(prev => ({
                        ...prev,
                        provincia: addr.county || addr.state_district || addr.city || '',
                        distrito:  addr.town || addr.village || addr.suburb || addr.municipality || '',
                    }))
                } catch {
                    // coordenadas guardadas igual
                }

                setGeoLoading(false)
            },
            () => {
                alert('No se pudo obtener la ubicación. Verifica los permisos.')
                setGeoLoading(false)
            }
        )
    }

    const handleSubmit = async (e) => {
        e.preventDefault()
        setLoading(true)
        setError(null)
        try {
            if (establecimiento) {
                await actualizarEstablecimiento(establecimiento.id, form)
            } else {
                await crearEstablecimiento(form)
            }
            onGuardado()
        } catch (err) {
            setError(err.response?.data?.message || 'Error al guardar.')
        } finally {
            setLoading(false)
        }
    }

    return (
        <form onSubmit={handleSubmit} className="space-y-4">
            {error && <p className="text-red-600 text-sm bg-red-50 p-3 rounded-lg">{error}</p>}

            <div>
                <label className="label">Nombre *</label>
                <input name="nombre" value={form.nombre} onChange={handleChange}
                    className="input" required />
            </div>

            <div>
                <label className="label">Descripción</label>
                <textarea name="descripcion" value={form.descripcion} onChange={handleChange}
                    rows={3} className="input" />
            </div>

            <div>
                <label className="label">Tipo</label>
                <select name="tipo_id" value={form.tipo_id} onChange={handleChange} className="input">
                    <option value="">— Seleccionar —</option>
                    {tipos.map(t => <option key={t.id} value={t.id}>{t.nombre}</option>)}
                </select>
            </div>

            <div className="grid grid-cols-2 gap-3">
                <div>
                    <label className="label">Apertura</label>
                    <input name="horario_apertura" type="time"
                        value={form.horario_apertura} onChange={handleChange} className="input" />
                </div>
                <div>
                    <label className="label">Cierre</label>
                    <input name="horario_cierre" type="time"
                        value={form.horario_cierre} onChange={handleChange} className="input" />
                </div>
            </div>

            <div className="flex items-center gap-2">
                <input name="tiene_internet" type="checkbox"
                    checked={form.tiene_internet} onChange={handleChange}
                    className="w-4 h-4 accent-green-600" />
                <label className="text-sm text-gray-700">Tiene internet / WiFi</label>
            </div>

            {/* Categorías */}
            {cats.length > 0 && (
                <div>
                    <label className="label">Categorías</label>
                    <div className="flex flex-wrap gap-2 mt-1">
                        {cats.map(c => (
                            <button key={c.id} type="button" onClick={() => toggleCategoria(c.id)}
                                className={`px-3 py-1 rounded-full text-xs font-medium border transition ${
                                    form.categorias.includes(c.id)
                                        ? 'bg-green-600 text-white border-green-600'
                                        : 'bg-white text-gray-600 border-gray-300 hover:border-green-400'
                                }`}>
                                {c.nombre}
                            </button>
                        ))}
                    </div>
                </div>
            )}

            {/* Ubicación */}
            <div className="border-t border-gray-100 pt-4">
                <div className="flex items-center justify-between mb-3">
                    <p className="text-sm font-semibold text-gray-700">Ubicación</p>
                    <button
                        type="button"
                        onClick={handleGeolocalizacion}
                        disabled={geoLoading}
                        className="text-xs bg-green-50 text-green-700 border border-green-200 px-3 py-1.5 rounded-lg font-medium hover:bg-green-100 transition disabled:opacity-50 flex items-center gap-1"
                    >
                        {geoLoading ? '📡 Obteniendo...' : '📍 Usar mi ubicación actual'}
                    </button>
                </div>

                <div className="grid grid-cols-2 gap-3">
                    <div>
                        <label className="label">Provincia</label>
                        <input name="provincia" value={form.provincia} onChange={handleChange} className="input" />
                    </div>
                    <div>
                        <label className="label">Distrito</label>
                        <input name="distrito" value={form.distrito} onChange={handleChange} className="input" />
                    </div>
                </div>
                <div className="mt-3">
                    <label className="label">Dirección</label>
                    <input name="direccion" value={form.direccion} onChange={handleChange} className="input" />
                </div>
                <div className="mt-3">
                    <label className="label">Referencia</label>
                    <input name="referencia" value={form.referencia} onChange={handleChange} className="input" />
                </div>
                <div className="grid grid-cols-2 gap-3 mt-3">
                    <div>
                        <label className="label">Latitud</label>
                        <input name="latitud" type="number" step="any"
                            value={form.latitud} onChange={handleChange}
                            className="input" placeholder="-6.00000000" />
                    </div>
                    <div>
                        <label className="label">Longitud</label>
                        <input name="longitud" type="number" step="any"
                            value={form.longitud} onChange={handleChange}
                            className="input" placeholder="-76.00000000" />
                    </div>
                </div>
                {form.latitud && form.longitud && (
                    <div className="mt-2 flex items-center justify-between">
                        <p className="text-xs text-green-600">
                            📍 {form.latitud}, {form.longitud}
                        </p>
                        
                        <a 
                            href={`https://maps.google.com/?q=${form.latitud},${form.longitud}`}
                            target="_blank"
                            rel="noopener noreferrer"
                            className="text-xs bg-blue-50 text-blue-600 border border-blue-200 px-3 py-1.5 rounded-lg font-medium hover:bg-blue-100 transition"
                        >
                            🗺️ Ver en Google Maps
                        </a>
                    </div>
                )}
            </div>

            <div className="flex gap-3 pt-2">
                <button type="submit" disabled={loading}
                    className="flex-1 bg-green-600 text-white py-2 rounded-xl font-semibold hover:bg-green-700 disabled:opacity-50 transition">
                    {loading ? 'Guardando...' : (establecimiento ? 'Actualizar' : 'Crear establecimiento')}
                </button>
                <button type="button" onClick={onCancelar}
                    className="flex-1 border border-gray-300 text-gray-600 py-2 rounded-xl font-semibold hover:bg-gray-50 transition">
                    Cancelar
                </button>
            </div>
        </form>
    )
}

function mapearForm(e) {
    return {
        nombre:           e.nombre || '',
        descripcion:      e.descripcion || '',
        tipo_id:          e.tipo?.id || '',
        tiene_internet:   e.tiene_internet || false,
        horario_apertura: e.horario_apertura || '',
        horario_cierre:   e.horario_cierre || '',
        categorias:       e.categorias?.map(c => c.id) || [],
        departamento:     e.ubicacion?.departamento || 'San Martín',
        provincia:        e.ubicacion?.provincia || '',
        distrito:         e.ubicacion?.distrito || '',
        direccion:        e.ubicacion?.direccion || '',
        referencia:       e.ubicacion?.referencia || '',
        latitud:          e.ubicacion?.latitud || '',
        longitud:         e.ubicacion?.longitud || '',
    }
}