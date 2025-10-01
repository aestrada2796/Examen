import './bootstrap';
import {createApp} from 'vue'
import TaskManager from './components/TaskManager.vue'

console.log('JavaScript is loading...')
console.log('TaskManager component:', TaskManager)

const app = createApp({})

app.component('task-manager', TaskManager)

console.log('Component registered')

// Verificar que el elemento existe antes de montar
document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM loaded')
    const appElement = document.getElementById('app')
    if (appElement) {
        console.log('Mounting Vue app...')
        app.mount('#app')
        console.log('Vue app mounted successfully')
    } else {
        console.error('Element with id "app" not found')
    }
})
