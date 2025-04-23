<template>
  <div>
    <h1 class="text-3xl font-bold mb-6 text-gray-900 dark:text-white">Gerenciamento do Torneio</h1>
    
    <div v-if="loading" class="text-center py-4">
      <div class="spinner"></div>
      <p class="mt-2 text-gray-600 dark:text-gray-300">Carregando dados do torneio...</p>
    </div>
    
    <div v-else-if="error" class="p-4 bg-red-100 dark:bg-red-300 text-red-700 dark:text-red-900 rounded-md mb-6">
      {{ error }}
    </div>
    
    <div v-else-if="!status || !status.ativo" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md text-gray-900 dark:text-gray-100">
      <h2 class="text-xl font-semibold mb-4">Iniciar Novo Torneio</h2>
      
      <p v-if="startups.length < 4" class="mb-4 text-red-500 dark:text-red-400">
        É necessário cadastrar pelo menos 4 startups para iniciar o torneio.
      </p>
      <p v-else-if="startups.length > 8" class="mb-4 text-red-500 dark:text-red-400">
        O torneio suporta no máximo 8 startups.
      </p>
      <p v-else-if="startups.length % 2 !== 0" class="mb-4 text-red-500 dark:text-red-400">
        O número de startups deve ser par.
      </p>
      <p v-else class="mb-4 text-green-500 dark:text-green-400">
        Tudo pronto para iniciar o torneio com {{ startups.length }} startups!
      </p>
      
      <div class="flex space-x-4">
        <router-link 
          to="/startups" 
          class="btn bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-100 hover:bg-gray-300 dark:hover:bg-gray-600"
        >
          Gerenciar Startups
        </router-link>
        <button 
          @click="iniciarTorneio"
          :disabled="!canStartTournament"
          :class="[
            'btn',
            canStartTournament 
              ? 'bg-primary text-white hover:bg-blue-700' 
              : 'bg-gray-300 dark:bg-gray-600 text-gray-600 dark:text-gray-300 cursor-not-allowed'
          ]"
        >
          Iniciar Torneio
        </button>
      </div>
    </div>
  </div>
</template>


<script>
import { computed, onMounted, watch } from 'vue'
import { useTorneioStore } from '../stores/torneio'
import { useStartupStore } from '../stores/startup'
import { useRouter } from 'vue-router'

export default {
  setup() {
    const torneioStore = useTorneioStore()
    const startupStore = useStartupStore()
    const router = useRouter()
    
    const loading = computed(() => torneioStore.loading)
    const error = computed(() => torneioStore.error)
    const status = computed(() => torneioStore.status)
    const startups = computed(() => startupStore.startups)
    
    const canStartTournament = computed(() => {
      const count = startups.value.length
      return count >= 4 && count <= 8 && count % 2 === 0
    })
    
    onMounted(async () => {
      try {
        await startupStore.fetchStartups()
        await torneioStore.fetchStatus()
      } catch (error) {
        console.error('Erro ao carregar dados iniciais:', error)
      }
    })
    
    watch(status, (newStatus) => {
      if (newStatus && newStatus.finalizado) {
        router.push('/relatorios')
      }
    })
    
    const iniciarTorneio = async () => {
      try {
        await torneioStore.iniciarTorneio()
        await torneioStore.fetchBatalhasPendentes()
        if (torneioStore.batalhasPendentes.length > 0) {
          router.push('/batalhas')
        }
      } catch (error) {
        console.error('Error starting tournament:', error)
      }
    }
    
    const avancarTorneio = async () => {
      try {
        await torneioStore.avancarTorneio()
        
        await Promise.all([
          torneioStore.fetchStatus(),
          torneioStore.fetchBatalhasPendentes()
        ])
        
        if (torneioStore.status.batalhas_pendentes > 0) {
          alert('Nova rodada gerada com sucesso!')
          router.push('/batalhas')
        } else if (torneioStore.status.finalizado) {
          router.push('/relatorios')
        }
      } catch (error) {
        console.error('Error advancing tournament:', error)
        alert(error.response?.data?.message || 'Erro ao avançar fase')
      }
    }
    
    return {
      loading,
      error,
      status,
      startups,
      canStartTournament,
      iniciarTorneio,
      avancarTorneio
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