import { useState } from 'react'
import { useNavigate, Link } from 'react-router-dom'
import { registerPersona, registerEmpresa } from '../services/authService'
import { useAuth } from '../context/AuthContext'

const initialPersona = {
    email: '',
    password: '',
    password_confirmation: '',
    nombre: '',
    apellido: '',
    dni: '',
    telefono: '',
}

const initialEmpresa = {
    email: '',
    password: '',
    password_confirmation: '',
    razon_social: '',
    ruc: '',
    telefono: '',
}

function Field({
    label,
    name,
    type = 'text',
    placeholder,
    value,
    onChange,
    errors
}) {
    return (
        <div>
            <label className="text-sm font-medium text-gray-700">
                {label}
            </label>

            <input
                type={type}
                name={name}
                value={value}
                onChange={onChange}
                placeholder={placeholder}
                className="mt-1 w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-400"
            />

            {errors[name] && (
                <p className="text-red-500 text-xs mt-1">
                    {errors[name][0]}
                </p>
            )}
        </div>
    )
}

export default function RegisterPage() {
    const navigate = useNavigate()
    const { login } = useAuth()

    const [tipo, setTipo] = useState('persona')
    const [form, setForm] = useState(initialPersona)
    const [errors, setErrors] = useState({})
    const [loading, setLoading] = useState(false)

    const [mostrarPassword, setMostrarPassword] = useState(false)

    const handleTipo = (t) => {
        setTipo(t)
        setForm(t === 'persona' ? initialPersona : initialEmpresa)
        setErrors({})
    }

    const handleChange = (e) => {
        setForm((prev) => ({
            ...prev,
            [e.target.name]: e.target.value,
        }))
    }

    const handleSubmit = async (e) => {
        e.preventDefault()
        setErrors({})
        setLoading(true)

        try {
            const fn =
                tipo === 'persona'
                    ? registerPersona
                    : registerEmpresa

            const { data } = await fn(form)

            login(data.token, data.usuario)

            navigate('/panel')
        } catch (err) {
            if (err.response?.status === 422) {
                setErrors(err.response.data.errors || {})
            }
        } finally {
            setLoading(false)
        }
    }

    return (
        <div className="min-h-screen bg-green-50 flex items-center justify-center px-4 py-10">
            <div className="bg-white rounded-2xl shadow-md w-full max-w-md p-8">

                <h2 className="text-2xl font-bold text-green-700 mb-1">
                    Crear cuenta
                </h2>

                <p className="text-gray-400 text-sm mb-6">
                    Únete a RutaSelva
                </p>

                <div className="flex rounded-xl overflow-hidden border border-gray-200 mb-6">
                    {['persona', 'empresa'].map((t) => (
                        <button
                            key={t}
                            type="button"
                            onClick={() => handleTipo(t)}
                            className={`flex-1 py-2 text-sm font-semibold transition ${
                                tipo === t
                                    ? 'bg-green-600 text-white'
                                    : 'bg-white text-gray-500 hover:bg-green-50'
                            }`}
                        >
                            {t === 'persona'
                                ? 'Soy persona'
                                : 'Soy empresa'}
                        </button>
                    ))}
                </div>

                <form
                    onSubmit={handleSubmit}
                    className="flex flex-col gap-4"
                >
                    <Field
                        label="Correo electrónico"
                        name="email"
                        type="email"
                        placeholder="correo@ejemplo.com"
                        value={form.email}
                        onChange={handleChange}
                        errors={errors}
                    />

                    <Field
                        label="Contraseña"
                        name="password"
                        type={mostrarPassword ? 'text' : 'password'}
                        placeholder="Mínimo 6 caracteres"
                        value={form.password}
                        onChange={handleChange}
                        errors={errors}
                    />

                    <Field
                        label="Confirmar contraseña"
                        name="password_confirmation"
                        type={mostrarPassword ? 'text' : 'password'}
                        placeholder="Repite la contraseña"
                        value={form.password_confirmation}
                        onChange={handleChange}
                        errors={errors}
                    />

                    <div className="flex items-center gap-2">
                        <input
                            id="mostrarPassword"
                            type="checkbox"
                            checked={mostrarPassword}
                            onChange={() =>
                                setMostrarPassword(!mostrarPassword)
                            }
                            className="cursor-pointer"
                        />

                        <label
                            htmlFor="mostrarPassword"
                            className="text-sm text-gray-600 cursor-pointer"
                        >
                            Mostrar contraseña
                        </label>
                    </div>

                    {tipo === 'persona' ? (
                        <>
                            <Field
                                label="Nombre"
                                name="nombre"
                                placeholder="Tu nombre"
                                value={form.nombre}
                                onChange={handleChange}
                                errors={errors}
                            />

                            <Field
                                label="Apellido"
                                name="apellido"
                                placeholder="Tu apellido"
                                value={form.apellido}
                                onChange={handleChange}
                                errors={errors}
                            />

                            <Field
                                label="DNI"
                                name="dni"
                                placeholder="8 dígitos"
                                value={form.dni}
                                onChange={handleChange}
                                errors={errors}
                            />

                            <Field
                                label="Teléfono (opcional)"
                                name="telefono"
                                placeholder="+51 999 999 999"
                                value={form.telefono}
                                onChange={handleChange}
                                errors={errors}
                            />
                        </>
                    ) : (
                        <>
                            <Field
                                label="Razón social"
                                name="razon_social"
                                placeholder="Nombre legal de la empresa"
                                value={form.razon_social}
                                onChange={handleChange}
                                errors={errors}
                            />

                            <Field
                                label="RUC"
                                name="ruc"
                                placeholder="11 dígitos"
                                value={form.ruc}
                                onChange={handleChange}
                                errors={errors}
                            />

                            <Field
                                label="Teléfono (opcional)"
                                name="telefono"
                                placeholder="+51 999 999 999"
                                value={form.telefono}
                                onChange={handleChange}
                                errors={errors}
                            />
                        </>
                    )}

                    <button
                        type="submit"
                        disabled={loading}
                        className="w-full py-2.5 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition disabled:opacity-60 mt-2"
                    >
                        {loading
                            ? 'Registrando...'
                            : 'Crear cuenta'}
                    </button>
                </form>

                <p className="text-sm text-center text-gray-500 mt-6">
                    ¿Ya tienes cuenta?{' '}
                    <Link
                        to="/login"
                        className="text-green-600 font-medium hover:underline"
                    >
                        Inicia sesión
                    </Link>
                </p>
            </div>
        </div>
    )
}