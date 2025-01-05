import Echo from 'laravel-echo';
import io from 'socket.io-client';
import { getCsrfToken } from '@/utils/csrf';

window.io = io;

const echo = new Echo({
  broadcaster: 'socket.io',
  host: 'http://localhost:6001',
  withCredentials: true,
  transports: ['websocket', 'polling'],
  authEndpoint: 'http://localhost:8000/broadcasting/auth',
  auth: {
    headers: {
      'X-XSRF-TOKEN': getCsrfToken(),
    }
  }
});

export default echo;
