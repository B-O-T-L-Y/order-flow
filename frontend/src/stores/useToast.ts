import {defineStore} from "pinia";
import {createTemplatePromise} from "@vueuse/core";
import {markRaw} from "vue";

export const useToast = defineStore('toast', () => {
  const ToastNotification = markRaw(createTemplatePromise<ToastMessage>({
    transition: {
      name: 'fade',
      appear: true,
    },
  }));

  const showToast = async (message: string, type: "success" | "error" | "info", delay: number = 10000): Promise<void> => {
    await ToastNotification.start(message, type, delay);
  }

  return {
    ToastNotification,
    showToast,
  };
});
