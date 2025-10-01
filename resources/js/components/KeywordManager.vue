<template>
  <div class="card mb-4">
    <div class="card-header">
      <h5>Keywords Management</h5>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
          <form @submit.prevent="handleCreateKeyword">
            <div class="input-group mb-3">
              <input
                type="text"
                class="form-control"
                v-model="newKeyword.name"
                placeholder="Enter keyword name"
                required
              >
              <button type="submit" class="btn btn-success" :disabled="loading">
                Add Keyword
              </button>
            </div>
          </form>
        </div>
        <div class="col-md-6">
          <div v-if="keywords.length > 0">
            <div class="d-flex flex-wrap gap-1">
              <span
                v-for="keyword in keywords"
                :key="keyword.id"
                class="badge bg-secondary d-flex align-items-center gap-1"
              >
                {{ keyword.name }}
                <button
                  @click="handleDeleteKeyword(keyword.id)"
                  class="btn-close btn-close-white"
                  style="font-size: 0.6em;"
                  :disabled="loading"
                ></button>
              </span>
            </div>
          </div>
          <div v-else class="text-muted">
            No keywords available
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

defineProps({
  keywords: {
    type: Array,
    default: () => []
  },
  loading: {
    type: Boolean,
    default: false
  }
})

// Emits
const emit = defineEmits(['create', 'delete'])

// Reactive data
const newKeyword = ref({
  name: ''
})

// Methods
const handleCreateKeyword = () => {
  if (!newKeyword.value.name.trim()) {
    alert('Please enter a keyword name')
    return
  }

  emit('create', { name: newKeyword.value.name })
  newKeyword.value = { name: '' }
}

const handleDeleteKeyword = (keywordId) => {
  if (!confirm('Are you sure you want to delete this keyword?')) {
    return
  }

  emit('delete', keywordId)
}
</script>

<style scoped>
.badge {
  font-size: 0.9em;
}
</style>
