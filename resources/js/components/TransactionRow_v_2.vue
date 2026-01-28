<template>
  <tr class="border-t hover:bg-gray-50 transition">
    <!-- Index -->
    <td class="px-4 py-2">
      {{ index + 1 }}
    </td>

    <!-- Dynamic columns via slot -->
    <slot />

    <!-- Actions -->
    <td class="px-4 py-2 text-center space-x-2">
      <button
        class="text-blue-600 hover:underline"
        @click="$emit('edit', item)"
      >
        Edit
      </button>

      <button
        class="text-red-600 hover:underline"
        @click="$emit('delete', item)"
      >
        Delete
      </button>
    </td>
  </tr>
</template>

<script setup>
defineProps({
  item: Object,
  index: Number
})

defineEmits(['edit', 'delete'])
</script>
