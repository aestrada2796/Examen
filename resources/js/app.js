import './bootstrap';
import {createApp} from 'vue'
import TaskList from './components/TaskList.vue'

console.log('JavaScript is loading...')

console.log('Vue components imported')

const app = createApp({})

app.component('task-list', TaskList)

console.log('Component registered')

// Verificar que el elemento existe antes de montar
document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM loaded')
    const appElement = document.getElementById('app')
    if (appElement) {
        app.mount('#app')
        console.log('Vue app mounted successfully')
    } else {
        console.error('Element with id "app" not found')
    }
})
