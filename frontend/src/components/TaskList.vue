<template>
  <div>
    <!-- Filters -->
    <div class="mb-4 flex gap-4">
      <select v-model="filterStatus" @change="fetchFiltered" class="border rounded p-2">
        <option value="">All</option>
        <option value="pending">Pending</option>
        <option value="completed">Completed</option>
      </select>
    </div>

    <!-- Draggable List -->
    <draggable v-model="taskStore.tasks" @end="onDragEnd" item-key="id">
      <template #item="{ element }">
        <div class="bg-white shadow p-4 mb-2 rounded flex justify-between items-center">
          <div>
            <h3 class="font-semibold">{{ element.title }}</h3>
            <p class="text-sm text-gray-500">{{ element.status }}</p>
          </div>
          <div class="space-x-2">
            <button @click="$emit('edit', element)" class="text-blue-500">Edit</button>
            <button @click="taskStore.deleteTask(element.id)" class="text-red-500">Delete</button>
          </div>
        </div>
      </template>
    </draggable>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import draggable from 'vuedraggable'
import { useTaskStore } from '@/store/task'

const taskStore = useTaskStore()
const filterStatus = ref('')

const fetchFiltered = () => {
  taskStore.fetchTasks({ status: filterStatus.value })
}

const onDragEnd = () => {
  const reordered = taskStore.tasks.map((task, index) => ({
    id: task.id,
    order: index,
  }))
  taskStore.reorderTasks(reordered)
}
</script>
