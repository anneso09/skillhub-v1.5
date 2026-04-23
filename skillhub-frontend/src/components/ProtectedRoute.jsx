import { Navigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';

export default function ProtectedRoute({ children, role }) {
  const { isAuthenticated, isFormateur, isApprenant, loading } = useAuth();

  if (loading) return null; // attend la rehydratation localStorage

  if (!isAuthenticated) return <Navigate to="/" replace />;

  if (role === 'formateur' && !isFormateur) {
    return <Navigate to="/dashboard/apprenant" replace />;
  }
  if (role === 'apprenant' && !isApprenant) {
    return <Navigate to="/dashboard/formateur" replace />;
  }

  return children;
}