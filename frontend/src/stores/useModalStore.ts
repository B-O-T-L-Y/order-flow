import {defineStore} from "pinia";
import {shallowRef, ref} from "vue";

export const useModalStore = defineStore('modal', () => {
  const visible = ref(false);
  const component = shallowRef<null | any>(null);
  const componentProps = ref<Record<string, any>>({});

  const openModal = (options: { component: any; props?: Record<string, any> }) => {
    component.value = options.component;
    componentProps.value = options.props || {};
    visible.value = true;
  };

  const closeModal = () => {
    visible.value = false;
    component.value = null;
    componentProps.value = {};
  };

  return {
    visible,
    component,
    componentProps,
    openModal,
    closeModal,
  }
});
