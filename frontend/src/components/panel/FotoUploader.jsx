import { useState } from 'react'
import { eliminarFoto, setPortada } from '../../services/platoService'
import api from '../../services/authService'

export default function FotoUploader({ establecimientoId, fotos = [], onActualizar }) {
    const [archivo, setArchivo]       = useState(null)
    const [preview, setPreview]       = useState(null)
    const [desc, setDesc]             = useState('')
    const [portada, setEsPortada]     = useState(false)
    const [loading, setLoading]       = useState(false)

    const handleArchivo = (e) => {
        const file = e.target.files[0]
        if (!file) return
        setArchivo(file)
        setPreview(URL.createObjectURL(file))
    }

    const handleAgregar = async () => {
        if (!archivo) return
        setLoading(true)
        try {
            const formData = new FormData()
            formData.append('foto',        archivo)
            formData.append('descripcion', desc)
            formData.append('es_portada',  portada ? '1' : '0')

            await api.post(
                `/establecimientos/${establecimientoId}/fotos`,
                formData,
                { headers: { 'Content-Type': 'multipart/form-data' } }
            )

            setArchivo(null)
            setPreview(null)
            setDesc('')
            setEsPortada(false)
            onActualizar()
        } catch {
            alert('Error al subir la foto.')
        } finally {
            setLoading(false)
        }
    }

    const handleEliminar = async (fotoId) => {
        if (!confirm('¿Eliminar esta foto?')) return
        await eliminarFoto(establecimientoId, fotoId)
        onActualizar()
    }

    const handlePortada = async (fotoId) => {
        await setPortada(establecimientoId, fotoId)
        onActualizar()
    }

    return (
        <div className="space-y-4">
            {/* Selector de archivo */}
            <div className="border-2 border-dashed border-gray-200 rounded-xl p-4 text-center">
                <input
                    type="file"
                    accept="image/jpeg,image/png,image/webp"
                    onChange={handleArchivo}
                    className="hidden"
                    id="foto-input"
                />
                <label htmlFor="foto-input" className="cursor-pointer">
                    {preview ? (
                        <img src={preview} alt="preview"
                            className="w-full h-40 object-cover rounded-lg mx-auto" />
                    ) : (
                        <div className="py-6 text-gray-400">
                            <p className="text-3xl mb-2">📷</p>
                            <p className="text-sm">Toca para seleccionar una foto</p>
                            <p className="text-xs mt-1">JPG, PNG o WEBP — máx. 3MB</p>
                        </div>
                    )}
                </label>
            </div>

            {archivo && (
                <div className="space-y-2">
                    <input
                        value={desc}
                        onChange={e => setDesc(e.target.value)}
                        placeholder="Descripción (opcional)"
                        className="input"
                    />
                    <div className="flex items-center gap-2">
                        <input type="checkbox" checked={portada}
                            onChange={e => setEsPortada(e.target.checked)}
                            className="w-4 h-4 accent-green-600" />
                        <span className="text-sm text-gray-600">Usar como portada</span>
                    </div>
                    <button onClick={handleAgregar} disabled={loading}
                        className="w-full bg-green-600 text-white py-2 rounded-xl font-semibold hover:bg-green-700 disabled:opacity-50 transition">
                        {loading ? 'Subiendo...' : 'Subir foto'}
                    </button>
                </div>
            )}

            {/* Galería actual */}
            {fotos.length > 0 && (
                <div className="border-t pt-4">
                    <p className="text-sm font-medium text-gray-700 mb-3">Fotos actuales</p>
                    <div className="grid grid-cols-2 gap-3">
                        {fotos.map(foto => (
                            <div key={foto.id}
                                className="relative group rounded-xl overflow-hidden border border-gray-200">
                                <img src={foto.url} alt={foto.descripcion}
                                    className="w-full h-28 object-cover" />
                                {foto.es_portada && (
                                    <span className="absolute top-2 left-2 bg-green-600 text-white text-xs px-2 py-0.5 rounded-full">
                                        Portada
                                    </span>
                                )}
                                <div className="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center gap-2">
                                    {!foto.es_portada && (
                                        <button onClick={() => handlePortada(foto.id)}
                                            className="text-xs bg-white text-gray-800 px-2 py-1 rounded-lg">
                                            Portada
                                        </button>
                                    )}
                                    <button onClick={() => handleEliminar(foto.id)}
                                        className="text-xs bg-red-500 text-white px-2 py-1 rounded-lg">
                                        Eliminar
                                    </button>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            )}
        </div>
    )
}