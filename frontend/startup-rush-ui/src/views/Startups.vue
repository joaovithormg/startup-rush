<template>
  <div>
    <h1 class="text-3xl font-bold mb-6">Cadastro de Startups</h1>
    
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md mb-8">
      <h2 class="text-xl font-semibold mb-4">Adicionar Nova Startup</h2>
      
      <form @submit.prevent="submitForm" class="space-y-4">
        <div>
          <label for="nome" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Nome da Startup</label>
          <input
            id="nome"
            v-model="form.nome"
            type="text"
            required
            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
            placeholder="Digite o nome da startup"
          >
        </div>
        
        <div>
          <label for="slogan" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Slogan</label>
          <input
            id="slogan"
            v-model="form.slogan"
            type="text"
            required
            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
            placeholder="Digite o slogan da startup"
          >
        </div>
        
        <div>
          <label for="ano_fundacao" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Ano de Fundação</label>
          <input
            id="ano_fundacao"
            v-model.number="form.ano_fundacao"
            type="number"
            required
            min="2000"
            :max="new Date().getFullYear()"
            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
            placeholder="Digite o ano de fundação"
          >
        </div>
        
        <div class="flex justify-end">
          <button type="submit" class="btn-primary">Cadastrar Startup</button>
        </div>
      </form>
    </div>
    
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
      <h2 class="text-xl font-semibold mb-4">Startups Cadastradas</h2>
      
      <div v-if="loading" class="text-center py-4">
        <div class="spinner"></div>
        <p class="mt-2 text-gray-600 dark:text-gray-300">Carregando startups...</p>
      </div>
      
      <div v-else-if="error" class="p-4 bg-red-100 dark:bg-red-300 text-red-700 dark:text-red-900 rounded-md">
        {{ error }}
      </div>
      
      <div v-else-if="startups.length === 0" class="text-center py-6 text-gray-500 dark:text-gray-400">
        Nenhuma startup cadastrada ainda. Adicione algumas para iniciar o torneio!
      </div>
      
      <div v-else class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
          <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nome</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Slogan</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ano de Fundação</th>
            </tr>
          </thead>
          <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
            <tr v-for="startup in startups" :key="startup.id">
              <td class="px-6 py-4 whitespace-nowrap text-gray-800 dark:text-gray-100">{{ startup.nome }}</td>
              <td class="px-6 py-4 text-gray-800 dark:text-gray-100">{{ startup.slogan }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-800 dark:text-gray-100">{{ startup.ano_fundacao }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <div class="mt-4 flex justify-between items-center">
        <div>
          <p class="text-sm text-gray-600 dark:text-gray-300">
            Total de startups: <span class="font-semibold">{{ startups.length }}</span>
          </p>
          <p class="text-sm text-gray-600 dark:text-gray-300" v-if="startups.length > 0">
            Necessárias: <span class="font-semibold">4-8 startups (número par)</span>
          </p>
        </div>
        
        <router-link to="/torneio" v-if="startups.length >= 4 && startups.length <= 8 && startups.length % 2 === 0" class="btn-secondary">
          Continuar para o Torneio
        </router-link>
        <button 
          v-else 
          disabled 
          class="btn bg-gray-300 dark:bg-gray-700 text-gray-600 dark:text-gray-300 cursor-not-allowed"
          title="Cadastre entre 4 e 8 startups (número par) para continuar"
        >
          Continuar para o Torneio
        </button>
      </div>
    </div>
  </div>
</template>

  
  <script>
  import { ref, onMounted, computed } from 'vue'
  import { useStartupStore } from '../stores/startup'
  
  export default {
    setup() {
      const startupStore = useStartupStore()
      
      const form = ref({
        nome: '',
        slogan: '',
        ano_fundacao: new Date().getFullYear()
      })
      
      const loading = computed(() => startupStore.loading)
      const error = computed(() => startupStore.error)
      const startups = computed(() => startupStore.startups)
      
      onMounted(() => {
        startupStore.fetchStartups()
      })
      
      const submitForm = async () => {
        try {
          await startupStore.createStartup(form.value)
          form.value = {
            nome: '',
            slogan: '',
            ano_fundacao: new Date().getFullYear()
          }
        } catch (error) {
          console.error('Error submitting form:', error)
        }
      }
      
      return {
        form,
        startups,
        loading,
        error,
        submitForm
      }
    }
  }
  </script>
  
  <style scoped>
  .spinner {
    border: 4px solid rgba(0, 0, 0, 0.1);
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border-left-color: #3B82F6;
    animation: spin 1s linear infinite;
    margin: 0 auto;
  }
  
  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }
  </style>