import { useState } from 'react'
import { crearPlato, actualizarPlato } from '../../services/platoService'
import api from '../../services/authService'

const INICIAL = { nombre: '', descripcion: '', precio: '', disponible: true }

export default function PlatoForm({ establecimientoId, plato = null, onGuardado, onCancelar }) {
    const [form, setForm]         = useState(plato
        ? { nombre: plato.nombre, descripcion: plato.descripcion, precio: plato.precio, disponible: plato.disponible }
        : INICIAL)
    const [archivo, setArchivo]   = useState(null)
    const [preview, setPreview]   = useState(plato?.foto_url || null)
    const [loading, setLoading]   = useState(false)

    const handleChange = (e) => {
        const { name, value, type, checked } = e.target
        setForm(prev => ({ ...prev, [name]: type === 'checkbox' ? checked : value }))
    }

    const handleArchivo = (e) => {
        const file = e.target.files[0]
        if (!file) return
        setArchivo(file)
        setPreview(URL.createObjectURL(file))
    }

    const handleSubmit = async (e) => {
        e.preventDefault()
        setLoading(true)
        try {
            const formData = new FormData()
            formData.append('nombre',      form.nombre)
            formData.append('descripcion', form.descripcion || '')
            formData.append('precio',      form.precio || '')
            formData.append('disponible',  form.disponible ? '1' : '0')
            if (archivo) formData.append('foto', archivo)

            if (plato) {
                await api.post(
                    `/establecimientos/${establecimientoId}/platos/${plato.id}?_method=PUT`,
                    formData,
                    { headers: { 'Content-Type': 'multipart/form-data' } }
                )
            } else {
                await api.post(
                    `/establecimientos/${establecimientoId}/platos`,
                    formData,
                    { headers: { 'Content-Type': 'multipart/form-data' } }
                )
            }
            onGuardado()
        } catch {
            alert('Error al guardar el plato.')
        } finally {
            setLoading(false)
        }
    }

    return (
        <form onSubmit={handleSubmit} className="space-y-3">
            <div>
                <label className="label">Nombre *</label>
                <input name="nombre" value={form.nombre} onChange={handleChange}
                    className="input" required />
            </div>
            <div>
                <label className="label">Descripción</label>
                <textarea name="descripcion" value={form.descripcion} onChange={handleChange}
                    rows={2} className="input" />
            </div>
            <div>
                <label className="label">Precio (S/)</label>
                <input name="precio" type="number" min="0" step="0.50"
                    value={form.precio} onChange={handleChange} className="input" />
            </div>

            {/* Foto del plato */}
            <div>
                <label className="label">Foto del plato</label>
                <div className="border-2 border-dashed border-gray-200 rounded-xl p-3 text-center">
                    <input type="file" accept="image/jpeg,image/png,image/webp"
                        onChange={handleArchivo} className="hidden" id="foto-plato-input" />
                    <label htmlFor="foto-plato-input" className="cursor-pointer">
                        {preview ? (
                            <img src={preview} alt="preview"
                                className="w-full h-32 object-cover rounded-lg" />
                        ) : (
                            <div className="py-4 text-gray-400">
                                <p className="text-2xl mb-1">🍽️</p>
                                <p className="text-xs">Toca para agregar foto</p>
                            </div>
                        )}
                    </label>
                </div>
            </div>

            <div className="flex items-center gap-2">
                <input name="disponible" type="checkbox"
                    checked={form.disponible} onChange={handleChange}
                    className="w-4 h-4 accent-green-600" />
                <label className="text-sm text-gray-700">Disponible</label>
            </div>

            <div className="flex gap-3">
                <button type="submit" disabled={loading}
                    className="flex-1 bg-green-600 text-white py-2 rounded-xl font-semibold hover:bg-green-700 disabled:opacity-50 transition">
                    {loading ? 'Guardando...' : (plato ? 'Actualizar' : 'Agregar plato')}
                </button>
                <button type="button" onClick={onCancelar}
                    className="flex-1 border border-gray-300 text-gray-600 py-2 rounded-xl font-semibold hover:bg-gray-50 transition">
                    Cancelar
                </button>
            </div>
        </form>
    )
}
