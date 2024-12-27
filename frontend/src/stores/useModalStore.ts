import {defineStore} from "pinia";
import {ref} from "vue";

export const useModalStore = defineStore('modal', () => {
  const isVisible = ref(false);
  const component = ref<any>(null);
  const componentProps = ref<Record<string, any>>({});

  const openModal = (modalComponent: any, props: Record<string, any> = {}) => {
    component.value = modalComponent;
    componentProps.value = props;
    isVisible.value = true;
  };

  const closeModal = () => {
    isVisible.value = false;
    component.value = null;
    componentProps.value = {};
  };

  return {
    isVisible,
    component,
    componentProps,
    openModal,
    closeModal,
  }
});
