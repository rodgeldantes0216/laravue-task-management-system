<template>
  <div>
    <nav class="bg-gray-800 text-white p-4 flex gap-4">
      <router-link to="/dashboard">Dashboard</router-link>
      <router-link to="/admin/users">Admin Users</router-link>
      <router-link to="/login">Logout</router-link>
    </nav>
    <main class="p-4">
      <router-view />
    </main>
  </div>
</template>