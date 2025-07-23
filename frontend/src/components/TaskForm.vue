<template>
  <form @submit.prevent="handleSubmit" class="bg-white p-4 rounded shadow">
    <input v-model="form.title" placeholder="Title" class="w-full mb-2 p-2 border rounded" />
    <select v-model="form.status" class="w-full mb-2 p-2 border rounded">
      <option value="pending">Pending</option>
      <option value="completed">Completed</option>
    </select>
    <div class="flex justify-end gap-2">
      <button type="submit" class="bg-blue-600 text-white px-4 py-1 rounded">
        {{ mode === 'edit' ? 'Update' : 'Create' }}
      </button>
      <button v-if="mode === 'edit'" type="button" @click="$emit('cancel')" class="text-gray-600">Cancel</button>
    </div>
  </form>
</template>

<script setup>
import { reactive, watch } from 'vue'
import { useTaskStore } from '@/store/task'

const props = defineProps({
  task: Object,
  mode: String
})
const emit = defineEmits(['submitted', 'cancel'])
const taskStore = useTaskStore()

const form = reactive({
  title: '',
  status: 'pending'
})

watch(() => props.task, (val) => {
  if (val) {
    form.title = val.title
    form.status = val.status
  }
}, { immediate: true })

const handleSubmit = async () => {
  if (props.mode === 'edit') {
    await taskStore.updateTask(props.task.id, form)
  } else {
    await taskStore.createTask(form)
  }
  emit('submitted')
}
</script>
