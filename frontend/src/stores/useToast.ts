import {defineStore} from "pinia";
import {createTemplatePromise} from "@vueuse/core";

export const useToast = defineStore('toast', () => {
  const Toast = createTemplatePromise<ToastMessage>({
    transition: {
      name: 'fade',
      appear: true,
    }
  });

  const showToast = async (message: string, type: "success" | "error" | "info"): Promise<void> => {
    const result = await Toast.start(message);
    console.log(result);
  };

  return {
    Toast,
    showToast,
  };
});
