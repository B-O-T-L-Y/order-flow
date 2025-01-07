import {useCookies} from "@vueuse/integrations/useCookies";

export function getCsrfToken(): string {
  const cookies = useCookies();

  return cookies.get("XSRF-TOKEN");

  // const matches = document.cookie.match(/XSRF-TOKEN=([^;]+)/);
  // return matches ? decodeURIComponent(matches[1]) : '';
}
