import { usePage } from '@inertiajs/react';

export default function Can({ permission, children }) {
  const { auth } = usePage().props;
  const user = auth?.user;

  if (!user?.permissions.includes(permission)) return null;
  return children;
}