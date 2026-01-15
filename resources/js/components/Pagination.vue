<template>
  <div class="pagination">
    <button
      :disabled="currentPage === 1"
      @click="changePage(currentPage - 1)"
    >
      Prev
    </button>

    <button
      v-for="page in pages"
      :key="page"
      :class="{ active: page === currentPage }"
      @click="changePage(page)"
    >
      {{ page }}
    </button>

    <button
      :disabled="currentPage === lastPage"
      @click="changePage(currentPage + 1)"
    >
      Next
    </button>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  currentPage: Number,
  lastPage: Number,
})

const emit = defineEmits(['page-changed'])

const pages = computed(() => {
  const pages = []
  for (let i = 1; i <= props.lastPage; i++) {
    pages.push(i)
  }
  return pages
})

const changePage = (page) => {
  if (page >= 1 && page <= props.lastPage) {
    emit('page-changed', page)
  }
}
</script>

<style scoped>
.pagination {
  display: flex;
  gap: 6px;
}

button {
  padding: 6px 12px;
  cursor: pointer;
}

button.active {
  background: #42b983;
  color: #fff;
}

button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}
</style>
