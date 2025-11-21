export async function get(endpoint) {
  const res = await fetch(`/mochiSearch/api/api.php?endpoint=${endpoint}`);
  return res.json();
}