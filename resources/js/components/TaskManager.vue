<template>
  <div class="container mt-4">
    <div class="row">
      <div class="col-md-8 mx-auto">
        <h2 class="mb-4">Task Manager</h2>
        
        <!-- Formulario para crear/editar tarea -->
        <TaskForm
          :keywords="keywords"
          :loading="loading"
          :editing-task="editingTask"
          @submit="handleTaskSubmit"
          @cancel="handleTaskCancel"
        />

        <!-- Gestión de palabras clave -->
        <KeywordManager
          :keywords="keywords"
          :loading="keywordsLoading"
          @create="handleKeywordCreate"
          @delete="handleKeywordDelete"
        />

        <!-- Lista de tareas -->
        <TaskList
          :tasks="tasks"
          :loading="loading"
          @toggle="handleTaskToggle"
          @edit="handleTaskEdit"
          @delete="handleTaskDelete"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import TaskForm from './TaskForm.vue'
import KeywordManager from './KeywordManager.vue'
import TaskList from './TaskList.vue'

// Reactive data
const tasks = ref([])
const keywords = ref([])
const loading = ref(false)
const keywordsLoading = ref(false)
const editingTask = ref(null)

// API base URL
const API_BASE = '/api'

// Methods
const fetchTasks = async () => {
  try {
    loading.value = true
    const response = await axios.get(`${API_BASE}/tasks`)
    tasks.value = response.data
  } catch (error) {
    console.error('Error fetching tasks:', error)
    alert('Error loading tasks')
  } finally {
    loading.value = false
  }
}

const fetchKeywords = async () => {
  try {
    const response = await axios.get(`${API_BASE}/keywords`)
    keywords.value = response.data
  } catch (error) {
    console.error('Error fetching keywords:', error)
  }
}

// Task methods
const handleTaskSubmit = async (taskData) => {
  try {
    loading.value = true
    
    if (editingTask.value) {
      // Update existing task
      const response = await axios.put(`${API_BASE}/tasks/${editingTask.value.id}`, taskData)
      const index = tasks.value.findIndex(task => task.id === editingTask.value.id)
      if (index !== -1) {
        tasks.value[index] = response.data
      }
      editingTask.value = null
    } else {
      // Create new task
      const response = await axios.post(`${API_BASE}/tasks`, taskData)
      tasks.value.push(response.data)
    }
  } catch (error) {
    console.error('Error saving task:', error)
    alert('Error saving task')
  } finally {
    loading.value = false
  }
}

const handleTaskCancel = () => {
  editingTask.value = null
}

const handleTaskEdit = (task) => {
  editingTask.value = task
}

const handleTaskDelete = async (taskId) => {
  try {
    loading.value = true
    await axios.delete(`${API_BASE}/tasks/${taskId}`)
    tasks.value = tasks.value.filter(task => task.id !== taskId)
  } catch (error) {
    console.error('Error deleting task:', error)
    alert('Error deleting task')
  } finally {
    loading.value = false
  }
}

const handleTaskToggle = async (taskId) => {
  try {
    loading.value = true
    const response = await axios.patch(`${API_BASE}/tasks/${taskId}/toggle`)
    
    const index = tasks.value.findIndex(task => task.id === taskId)
    if (index !== -1) {
      tasks.value[index] = response.data
    }
  } catch (error) {
    console.error('Error toggling task:', error)
    alert('Error updating task status')
  } finally {
    loading.value = false
  }
}

// Keyword methods
const handleKeywordCreate = async (keywordData) => {
  try {
    keywordsLoading.value = true
    const response = await axios.post(`${API_BASE}/keywords`, keywordData)
    keywords.value.push(response.data)
  } catch (error) {
    console.error('Error creating keyword:', error)
    if (error.response?.status === 422) {
      alert('This keyword already exists')
    } else {
      alert('Error creating keyword')
    }
  } finally {
    keywordsLoading.value = false
  }
}

const handleKeywordDelete = async (keywordId) => {
  try {
    keywordsLoading.value = true
    await axios.delete(`${API_BASE}/keywords/${keywordId}`)
    
    keywords.value = keywords.value.filter(keyword => keyword.id !== keywordId)
    
    // Remove keyword from all tasks
    tasks.value.forEach(task => {
      if (task.keywords) {
        task.keywords = task.keywords.filter(k => k.id !== keywordId)
      }
    })
  } catch (error) {
    console.error('Error deleting keyword:', error)
    alert('Error deleting keyword')
  } finally {
    keywordsLoading.value = false
  }
}

// Lifecycle
onMounted(() => {
  fetchTasks()
  fetchKeywords()
})
</script>

<style scoped>
.card {
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
</style>
