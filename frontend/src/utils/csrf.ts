export function getCsrfToken(): string {
  const matches = document.cookie.match(/XSRF-TOKEN=([^;]+)/);
  return matches ? decodeURIComponent(matches[1]) : '';
}
