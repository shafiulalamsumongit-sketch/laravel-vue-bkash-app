<template>
  <tr class="border-t hover:bg-gray-50 transition">
    
    <!-- Serial -->
    <td class="px-4 py-2 w-12 text-center">
      {{ index + 1 }}
    </td>

    <!-- Dynamic columns -->
    <slot :item="item" />

    <!-- Actions -->
    <td class="px-4 py-2 text-center space-x-2">
      <button class="text-blue-600 hover:underline" @click="$emit('edit', item)">
        Edit
      </button>

      <button class="text-red-600 hover:underline" @click="$emit('delete', item)">
        Delete
      </button>
    </td>
  </tr>
</template>

<script setup>
defineProps({
  item: { type: Object, required: true },
  index: { type: Number, required: true },
});

defineEmits(["edit", "delete"]);
</script>
