<template>
  <div class="card mb-4">
    <div class="card-header">
      <h5>{{ editingTask ? 'Edit Task' : 'Create New Task' }}</h5>
    </div>
    <div class="card-body">
      <form @submit.prevent="handleSubmit">
        <div class="mb-3">
          <label for="taskTitle" class="form-label">Task Title</label>
          <input 
            type="text" 
            class="form-control" 
            id="taskTitle" 
            v-model="formData.title"
            placeholder="Enter task title"
            required
          >
        </div>
        <div class="mb-3">
          <label for="keywords" class="form-label">Keywords</label>
          <select 
            multiple 
            class="form-select" 
            id="keywords" 
            v-model="formData.keyword_ids"
            size="3"
          >
            <option v-for="keyword in keywords" :key="keyword.id" :value="keyword.id">
              {{ keyword.name }}
            </option>
          </select>
          <div class="form-text">Hold Ctrl to select multiple keywords</div>
        </div>
        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-primary" :disabled="loading">
            <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
            {{ editingTask ? 'Update Task' : 'Create Task' }}
          </button>
          <button v-if="editingTask" type="button" @click="cancelEdit" class="btn btn-secondary">
            Cancel
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'

// Props
const props = defineProps({
  keywords: {
    type: Array,
    default: () => []
  },
  loading: {
    type: Boolean,
    default: false
  },
  editingTask: {
    type: Object,
    default: null
  }
})

// Emits
const emit = defineEmits(['submit', 'cancel'])

// Reactive data
const formData = ref({
  title: '',
  keyword_ids: []
})

// Methods
const resetForm = () => {
  formData.value = {
    title: '',
    keyword_ids: []
  }
}

const handleSubmit = () => {
  if (!formData.value.title.trim()) {
    alert('Please enter a task title')
    return
  }

  emit('submit', {
    title: formData.value.title,
    keyword_ids: formData.value.keyword_ids
  })
}

const cancelEdit = () => {
  resetForm()
  emit('cancel')
}

// Watch for editing task changes
watch(() => props.editingTask, (newTask) => {
  if (newTask) {
    formData.value = {
      title: newTask.title,
      keyword_ids: newTask.keywords ? newTask.keywords.map(k => k.id) : []
    }
  } else {
    resetForm()
  }
}, { immediate: true })
</script>

<style scoped>
.spinner-border-sm {
  width: 1rem;
  height: 1rem;
}
</style>
