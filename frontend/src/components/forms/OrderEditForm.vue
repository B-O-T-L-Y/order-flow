<script setup lang="ts">
import {useOrdersStore} from "@/stores/useOrdersStore.ts";
import {reactive, ref} from "vue";

const props = defineProps({
  order: {
    type: Object as () => Order,
    required: true,
  },
  onSave: Function,
  onCancel: Function,
});

const orderStore = useOrdersStore();

const form = reactive({
  status: props.order.status,
});

const errors = ref<Record<string, string[]>>({});

const saveChanges = async (): Promise<void> => {
  errors.value = {};
  if (!form.status) {
    errors.value = {status: ['Status is required']};
    return;
  }

  const {error} = await props.onSave({...form});

  if (error) {
    errors.value = error.value?.body.error.details;
  }
};
</script>

<template>
  <form class="max-w-sm mx-auto p-4 space-y-6">
    <h5 class="text-xl font-medium text-gray-900 dark:text-white">Edit Order</h5>
    <div>
      <label for="status" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select status</label>
      <select
        v-model="form.status"
        id="status"
        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
      >
        <option
          v-for="status in orderStore.predefineStatuses.filter(status => status.value)"
          :key="status.value"
          :value="status.value"
        >
          {{ status.label }}
        </option>
      </select>
    </div>
    <p v-if="errors?.status" class="mt-2 text-sm text-red-600 dark:text-red-500">{{ errors.status[0] }}</p>
    <p v-if="errors?.form" class="mt-2 text-sm text-red-600 dark:text-red-500">{{ errors.form[0] }}</p>
    <button
      @click="saveChanges"
      type="button"
      class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
    >
      Save
    </button>
    <button
      @click="props.onCancel"
      type="button"
      class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
    >
      Cancel
    </button>
  </form>
</template>
