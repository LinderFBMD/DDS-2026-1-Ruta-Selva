import { BrowserRouter, Routes, Route, Navigate } from "react-router-dom";
import { AuthProvider, useAuth } from "../context/AuthContext"; // IMPORTADO: useAuth para leer el usuario
import ProtectedRoute from "../context/ProtectedRoute";

import HomePage           from "../pages/HomePage";
import AccesoPage         from "../pages/AccesoPage";
import LoginPage          from "../pages/LoginPage";
import RegisterPage       from "../pages/RegisterPage";
import PanelPage          from "../pages/PanelPage";
import EstablecimientoPage  from "../pages/EstablecimientoPage";
import MisPublicacionesPage from "../pages/MisPublicacionesPage";
import SuscripcionPage      from "../pages/SuscripcionPage";

// Componente guardián para saltarse el menú intermedio si es un usuario común
function PanelRedirectGuard({ children }) {
  const { user } = useAuth(); // O como se llame el objeto usuario en tu AuthContext

  // Si el usuario está autenticado y NO es una empresa (es decir, es una persona/cliente)
  // lo mandamos directo al Home ("/") para que explore los establecimientos
  if (user && user.tipo_usuario !== 'empresa' && user.role !== 'empresa') { 
    return <Navigate to="/" replace />;
  }

  // Si es empresa, sí necesita su panel para administrar publicaciones o suscripciones
  return children;
}

export default function AppRouter() {
  return (
    <BrowserRouter>
      <AuthProvider>
        <Routes>
          {/* Públicas */}
          <Route path="/"                    element={<HomePage />} />
          <Route path="/acceso"    element={<AccesoPage />} />
          <Route path="/login"     element={<LoginPage />} />
          <Route path="/registro"  element={<RegisterPage />} />
          <Route path="/establecimiento/:id" element={<EstablecimientoPage />} />

          {/* Protegidas */}
          <Route
            path="/panel"
            element={
              <ProtectedRoute>
                {/* Envolvemos el Panel en el guardián para filtrar el acceso */}
                <PanelRedirectGuard>
                  <PanelPage />
                </PanelRedirectGuard>
              </ProtectedRoute>
            }
          />
          <Route
            path="/panel/mis-publicaciones"
            element={
              <ProtectedRoute>
                <MisPublicacionesPage />
              </ProtectedRoute>
            }
          />
          <Route
            path="/panel/suscripcion"
            element={
              <ProtectedRoute>
                <SuscripcionPage />
              </ProtectedRoute>
            }
          />

          {/* Fallback */}
          <Route path="*" element={<Navigate to="/" replace />} />
        </Routes>
      </AuthProvider>
    </BrowserRouter>
  );
}