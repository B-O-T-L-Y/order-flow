<script setup lang="ts">

</script>

<template>
  <div class="mx-auto w-full max-w-sm p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-6 md:p-8 dark:bg-gray-800 dark:border-gray-700">
    <form>
      <div class="mb-5">
        <label for="username-success" class="block mb-2 text-sm font-medium text-green-700 dark:text-green-500">Your name</label>
        <input type="text" id="username-success"
               class="bg-green-50 border border-green-500 text-green-900 dark:text-green-400 placeholder-green-700 dark:placeholder-green-500 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 dark:bg-gray-700 dark:border-green-500"
               placeholder="Bonnie Green">
        <p class="mt-2 text-sm text-green-600 dark:text-green-500"><span class="font-medium">Alright!</span> Username available!</p>
      </div>
      <div>
        <label for="username-error" class="block mb-2 text-sm font-medium text-red-700 dark:text-red-500">Your name</label>
        <input type="text" id="username-error"
               class="bg-red-50 border border-red-500 text-red-900 placeholder-red-700 text-sm rounded-lg focus:ring-red-500 dark:bg-gray-700 focus:border-red-500 block w-full p-2.5 dark:text-red-500 dark:placeholder-red-500 dark:border-red-500"
               placeholder="Bonnie Green">
        <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">Oops!</span> Username already taken!</p>
      </div>
    </form>
  </div>
</template>
