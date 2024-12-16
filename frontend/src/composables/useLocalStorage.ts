import {onScopeDispose, ref, watch} from "vue";

export default function useLocalstorage<T>(key: string, defaultValue?: T) {
  const val = ref<T>(defaultValue);

  const storageValue = localStorage.getItem(key);

  if (storageValue) {
    val.value = JSON.parse(storageValue);
  }

  watch(
    val,
    (newValue) => window.localStorage.setItem(key, JSON.stringify(newValue)),
    {deep: true},
  )

  function handleStorageEvent(event: StorageEvent) {
    if (event.key === key) {
      val.value = JSON.parse(event.newValue || 'null');
    }
  }

  window.addEventListener('storage', handleStorageEvent);

  onScopeDispose(() => {
    window.removeEventListener('storage', handleStorageEvent);
  });

  return val;
}
