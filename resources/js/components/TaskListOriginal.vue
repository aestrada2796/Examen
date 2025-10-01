<template>
  <div class="card">
    <div class="card-header">
      <h5>Tasks</h5>
    </div>
    <div class="card-body">
      <div v-if="loading" class="text-center">
        <div class="spinner-border" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
      </div>
      <div v-else-if="tasks.length === 0" class="text-center text-muted">
        No tasks found. Create your first task above.
      </div>
      <div v-else>
        <TaskItem
          v-for="task in tasks"
          :key="task.id"
          :task="task"
          :loading="loading"
          @toggle="handleToggle"
          @edit="handleEdit"
          @delete="handleDelete"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import TaskItem from './TaskItem.vue'

// Props
const props = defineProps({
  tasks: {
    type: Array,
    default: () => []
  },
  loading: {
    type: Boolean,
    default: false
  }
})

// Emits
const emit = defineEmits(['toggle', 'edit', 'delete'])

// Methods
const handleToggle = (taskId) => {
  emit('toggle', taskId)
}

const handleEdit = (task) => {
  emit('edit', task)
}

const handleDelete = (taskId) => {
  emit('delete', taskId)
}
</script>

<style scoped>
.card {
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
</style>