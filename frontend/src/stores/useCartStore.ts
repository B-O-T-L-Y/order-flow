import {defineStore} from "pinia";
import useLocalstorage from "@/composables/useLocalStorage.ts";
import {computed} from "vue";
import {useApiFetch} from "@/composables/useApiFetch.ts";
import router from "@/router";

export const useCartStore = defineStore('cart', () => {
  const cart = useLocalstorage<CartItem[]>('cart', []);

  const syncCartWithServer = async (userId: number): Promise<void> => {
    if (cart.value.length > 0) {
      await useApiFetch(`/api/users/${userId}sync-cart`, {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({items: cart.value}),
      });
    }
  };

  const addToCart = (product: Product, quantity = 1) => {
    const item = cart.value.find(item => item.product.id === product.id);

    if (item) {
      item.quantity += quantity;
    } else {
      cart.value.push({product, quantity});
    }
  };

  const updateQuantity = (productId: number, quantity: number) => {
    const item = cart.value.find(item => item.product.id === productId);

    if (item) {
      item.quantity = quantity;
    }
  };

  const removeFromCart = (productId: number) => {
    cart.value = cart.value.filter(item => item.product.id !== productId);
  };

  const clearCart = async () => {
    cart.value = [];
  };

  const totalAmount = computed(() =>
    Number(cart.value.reduce((sum, item) => sum + item.product.price * item.quantity, 0).toFixed(2))
  );

  const checkout = async () => {
    const payload = {
      products: cart.value.map(item => ({
        product_id: item.product.id,
        quantity: item.quantity,
      })),
    };

    const {data, error, statusCode} = await useApiFetch('/api/orders', {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify(payload),
    }).json();

    return {data, error, statusCode};
  };

  return {
    cart,
    totalAmount,
    addToCart,
    updateQuantity,
    removeFromCart,
    clearCart,
    checkout
  };
});
