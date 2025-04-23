<template>
  <div class="min-h-screen bg-gray-100 dark:bg-gray-900 dark:text-white transition-colors duration-300">
    <header class="bg-white dark:bg-gray-800 shadow">
      <nav class="container mx-auto px-4 py-4 flex justify-between items-center">
        <div class="text-2xl font-bold text-primary">Startup Rush</div>
        <div class="flex items-center space-x-4">
          <ul class="hidden md:flex space-x-4">
            <li><router-link to="/" class="text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-primary">Home</router-link></li>
            <li><router-link to="/startups" class="text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-primary">Startups</router-link></li>
            <li><router-link to="/torneio" class="text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-primary">Torneio</router-link></li>
            <li><router-link to="/batalhas" class="text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-primary">Batalhas</router-link></li>
            <li><router-link to="/relatorios" class="text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-primary">Relatórios</router-link></li>
          </ul>
          <button @click="toggleDarkMode" type="button" class="ml-4 p-2 text-gray-500 dark:text-gray-300 focus:outline-none">
            <span v-if="darkMode" class="text-yellow-400">☀️</span>
            <span v-else class="text-gray-600">🌙</span>
          </button>
          <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-gray-500 dark:text-gray-300 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>
        </div>
      </nav>
      
      <div v-if="mobileMenuOpen" class="md:hidden">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
          <router-link to="/" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Home</router-link>
          <router-link to="/startups" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Startups</router-link>
          <router-link to="/torneio" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Torneio</router-link>
          <router-link to="/batalhas" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Batalhas</router-link>
          <router-link to="/relatorios" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Relatórios</router-link>
        </div>
      </div>
    </header>
    <main class="container mx-auto px-4 py-8">
      <router-view />
    </main>
    <footer class="bg-white dark:bg-gray-800 shadow mt-8 py-4">
      <div class="container mx-auto px-4 text-center text-gray-500 dark:text-gray-400">
        <p>Startup Rush &copy; {{ new Date().getFullYear() }}</p>
      </div>
    </footer>
  </div>
</template>

<script>
import { ref } from 'vue'
import { useThemeStore } from './stores/theme'
import { storeToRefs } from 'pinia'

export default {
  setup() {
    const themeStore = useThemeStore()
    const { darkMode } = storeToRefs(themeStore)
    const mobileMenuOpen = ref(false)
    
    const toggleDarkMode = () => {
      themeStore.toggleDarkMode()
    }
    
    return {
      darkMode,
      mobileMenuOpen,
      toggleDarkMode
    }
  }
}
</script>