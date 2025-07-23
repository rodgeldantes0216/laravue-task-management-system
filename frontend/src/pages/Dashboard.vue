<template>
  <div class="max-w-2xl mx-auto p-6">
    <task-form v-if="showForm" :mode="formMode" :task="editingTask" @submitted="closeForm" @cancel="closeForm" />
    <button @click="newTask" class="bg-green-600 text-white px-4 py-2 rounded mb-4" v-if="!showForm">+ New Task</button>
    <button @click="handleLogout">Logout</button>
    <task-list @edit="editTask" />
  </div>
</template>

<script setup>
import TaskList from '@/components/TaskList.vue'
import TaskForm from '@/components/TaskForm.vue'
import { ref, onMounted } from 'vue'
import { useTaskStore } from '@/store/task'
import { useRouter } from 'vue-router'
import { useUserStore } from '@/store/user'

const taskStore = useTaskStore()
const showForm = ref(false)
const editingTask = ref(null)
const formMode = ref('create')

onMounted(() => {
  taskStore.fetchTasks()
})

const newTask = () => {
  editingTask.value = null
  formMode.value = 'create'
  showForm.value = true
}

const editTask = (task) => {
  editingTask.value = task
  formMode.value = 'edit'
  showForm.value = true
}

const closeForm = () => {
  showForm.value = false
}

const router = useRouter()
const store = useUserStore()

const handleLogout = async () => {
  await store.logout()
  router.push('/user') // redirect to login page
}
</script>
