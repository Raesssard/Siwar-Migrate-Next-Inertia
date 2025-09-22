import { usePage } from '@inertiajs/react';

export default function Role({ roles, children }) {
  const { auth } = usePage().props;
  const userRoles = auth?.user?.roles || [];

  const allowedRoles = Array.isArray(roles) ? roles : [roles];

  const hasRole = userRoles.some(role => allowedRoles.includes(role));

  if (!hasRole) return null;

  return children;
}
