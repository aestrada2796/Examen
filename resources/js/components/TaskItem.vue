<template>
  <div class="card mb-3">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-start">
        <div class="flex-grow-1">
          <h6 class="card-title mb-2">
            <span :class="{ 'text-decoration-line-through': task.is_done }">
              {{ task.title }}
            </span>
          </h6>
          <div class="mb-2">
            <span class="badge" :class="task.is_done ? 'bg-success' : 'bg-warning'">
              {{ task.is_done ? 'Completed' : 'Pending' }}
            </span>
          </div>
          <div v-if="task.keywords && task.keywords.length > 0" class="mb-2">
            <span class="text-muted small">Keywords: </span>
            <span 
              v-for="(keyword, index) in task.keywords" 
              :key="keyword.id"
              class="badge bg-secondary me-1"
            >
              {{ keyword.name }}
            </span>
          </div>
        </div>
        <div class="ms-3 d-flex gap-1">
          <button 
            @click="handleToggle" 
            class="btn btn-sm"
            :class="task.is_done ? 'btn-outline-warning' : 'btn-outline-success'"
            :disabled="loading"
          >
            {{ task.is_done ? 'Mark Pending' : 'Mark Complete' }}
          </button>
          <button 
            @click="handleEdit" 
            class="btn btn-sm btn-outline-primary"
            :disabled="loading"
          >
            Edit
          </button>
          <button 
            @click="handleDelete" 
            class="btn btn-sm btn-outline-danger"
            :disabled="loading"
          >
            Delete
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
// Props
const props = defineProps({
  task: {
    type: Object,
    required: true
  },
  loading: {
    type: Boolean,
    default: false
  }
})

// Emits
const emit = defineEmits(['toggle', 'edit', 'delete'])

// Methods
const handleToggle = () => {
  emit('toggle', props.task.id)
}

const handleEdit = () => {
  emit('edit', props.task)
}

const handleDelete = () => {
  if (!confirm('Are you sure you want to delete this task?')) {
    return
  }
  emit('delete', props.task.id)
}
</script>

<style scoped>
.badge {
  font-size: 0.75em;
}
</style>
