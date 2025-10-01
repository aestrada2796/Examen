<template>
  <div class="container mt-4">
    <div class="row">
      <div class="col-md-8 mx-auto">
        <h2 class="mb-4">Task Manager</h2>
        
        <!-- Formulario para crear/editar tarea -->
        <div class="card mb-4">
          <div class="card-header">
            <h5>{{ editingTask ? 'Edit Task' : 'Create New Task' }}</h5>
          </div>
          <div class="card-body">
            <form @submit.prevent="editingTask ? updateTask() : createTask()">
              <div class="mb-3">
                <label for="taskTitle" class="form-label">Task Title</label>
                <input 
                  type="text" 
                  class="form-control" 
                  id="taskTitle" 
                  v-model="newTask.title"
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
                  v-model="newTask.keyword_ids"
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

        <!-- Gestión de palabras clave -->
        <div class="card mb-4">
          <div class="card-header">
            <h5>Keywords Management</h5>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <form @submit.prevent="createKeyword">
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
                        @click="deleteKeyword(keyword.id)" 
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

        <!-- Lista de tareas -->
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
              <div v-for="task in tasks" :key="task.id" class="card mb-3">
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
                        @click="toggleTask(task.id)" 
                        class="btn btn-sm"
                        :class="task.is_done ? 'btn-outline-warning' : 'btn-outline-success'"
                        :disabled="loading"
                      >
                        {{ task.is_done ? 'Mark Pending' : 'Mark Complete' }}
                      </button>
                      <button 
                        @click="editTask(task)" 
                        class="btn btn-sm btn-outline-primary"
                        :disabled="loading"
                      >
                        Edit
                      </button>
                      <button 
                        @click="deleteTask(task.id)" 
                        class="btn btn-sm btn-outline-danger"
                        :disabled="loading"
                      >
                        Delete
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

// Reactive data
const tasks = ref([])
const keywords = ref([])
const loading = ref(false)
const editingTask = ref(null)
const newTask = ref({
  title: '',
  keyword_ids: []
})
const newKeyword = ref({
  name: ''
})

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

const createTask = async () => {
  if (!newTask.value.title.trim()) {
    alert('Please enter a task title')
    return
  }

  try {
    loading.value = true
    const response = await axios.post(`${API_BASE}/tasks`, {
      title: newTask.value.title,
      keyword_ids: newTask.value.keyword_ids
    })
    
    tasks.value.push(response.data)
    newTask.value = { title: '', keyword_ids: [] }
  } catch (error) {
    console.error('Error creating task:', error)
    alert('Error creating task')
  } finally {
    loading.value = false
  }
}

const editTask = (task) => {
  editingTask.value = task
  newTask.value = {
    title: task.title,
    keyword_ids: task.keywords ? task.keywords.map(k => k.id) : []
  }
}

const updateTask = async () => {
  if (!newTask.value.title.trim()) {
    alert('Please enter a task title')
    return
  }

  try {
    loading.value = true
    const response = await axios.put(`${API_BASE}/tasks/${editingTask.value.id}`, {
      title: newTask.value.title,
      keyword_ids: newTask.value.keyword_ids
    })
    
    const index = tasks.value.findIndex(task => task.id === editingTask.value.id)
    if (index !== -1) {
      tasks.value[index] = response.data
    }
    
    cancelEdit()
  } catch (error) {
    console.error('Error updating task:', error)
    alert('Error updating task')
  } finally {
    loading.value = false
  }
}

const deleteTask = async (taskId) => {
  if (!confirm('Are you sure you want to delete this task?')) {
    return
  }

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

const cancelEdit = () => {
  editingTask.value = null
  newTask.value = { title: '', keyword_ids: [] }
}

const createKeyword = async () => {
  if (!newKeyword.value.name.trim()) {
    alert('Please enter a keyword name')
    return
  }

  try {
    loading.value = true
    const response = await axios.post(`${API_BASE}/keywords`, {
      name: newKeyword.value.name
    })
    
    keywords.value.push(response.data)
    newKeyword.value = { name: '' }
  } catch (error) {
    console.error('Error creating keyword:', error)
    if (error.response?.status === 422) {
      alert('This keyword already exists')
    } else {
      alert('Error creating keyword')
    }
  } finally {
    loading.value = false
  }
}

const deleteKeyword = async (keywordId) => {
  if (!confirm('Are you sure you want to delete this keyword?')) {
    return
  }

  try {
    loading.value = true
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
    loading.value = false
  }
}

const toggleTask = async (taskId) => {
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

.badge {
  font-size: 0.75em;
}

.spinner-border-sm {
  width: 1rem;
  height: 1rem;
}
</style>
