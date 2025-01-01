import {defineStore} from "pinia";
import useLocalstorage from "@/composables/useLocalStorage.ts";
import {computed} from "vue";

export const useCartStore = defineStore('cart', () => {
  const cart = useLocalstorage<CartItem[]>('cart', []);

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

  const clearCart = () => {
    cart.value = [];
  };

  const totalAmount = computed(() =>
    Number(cart.value.reduce((sum, item) => sum + item.product.price * item.quantity, 0).toFixed(2))
  );

  return {
    cart,
    addToCart,
    updateQuantity,
    removeFromCart,
    clearCart,
    totalAmount,
  };
});
