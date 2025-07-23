<template>
  <div class="flex items-center justify-center h-screen bg-gray-100">
    <div class="bg-white p-6 rounded shadow w-96">
      <h2 class="text-xl font-bold mb-4">{{ isRegister ? 'Register' : 'Login' }}</h2>

      <form @submit.prevent="isRegister ? handleRegister() : handleLogin()">
        <div v-if="isRegister">
          <input v-model="form.name" type="text" placeholder="Name"
                 class="w-full mb-3 px-3 py-2 border rounded" required />
        </div>

        <input v-model="form.email" type="email" placeholder="Email"
               class="w-full mb-3 px-3 py-2 border rounded" required />

        <input v-model="form.password" type="password" placeholder="Password"
               class="w-full mb-3 px-3 py-2 border rounded" required />

        <div v-if="isRegister">
          <input v-model="form.password_confirmation" type="password"
                 placeholder="Confirm Password"
                 class="w-full mb-4 px-3 py-2 border rounded" required />
        </div>

        <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded w-full mb-3">
          {{ isRegister ? 'Register' : 'Login' }}
        </button>
      </form>

      <p class="text-center text-sm">
        <span v-if="isRegister">
          Already have an account?
          <button @click="toggleForm" class="text-blue-600 underline">Login</button>
        </span>
        <span v-else>
          Don’t have an account?
          <button @click="toggleForm" class="text-blue-600 underline">Register</button>
        </span>
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import { useUserStore } from '@/store/user'
import { useRouter, useRoute } from 'vue-router'

const router = useRouter()
const route = useRoute()
const store = useUserStore()

const isRegister = ref(false)

// Watch the "user" query parameter and update the form
watch(
  () => route.query.user,
  (value) => {
    isRegister.value = value === 'register'
  },
  { immediate: true }
)

const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: ''
})

const handleLogin = async () => {
  try {
    await store.login({ email: form.value.email, password: form.value.password })
    router.push('/dashboard')
  } catch (err) {
    alert('Invalid credentials')
  }
}

const handleRegister = async () => {
  try {
    await store.register(form.value)
    router.push('/dashboard')
  } catch (err) {
    alert('Registration failed')
    console.error(err)
  }
}

const toggleForm = () => {
  isRegister.value = !isRegister.value
  router.replace({ query: { user: isRegister.value ? 'register' : 'login' } })
}
</script>