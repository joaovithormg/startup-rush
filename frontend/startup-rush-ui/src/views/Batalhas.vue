<template>
  <div>
    <h1 class="text-3xl font-bold mb-6 dark:text-white">Batalhas Pendentes</h1>

    <div v-if="loading" class="text-center py-4">
      <div class="spinner"></div>
      <p class="mt-2 text-gray-600 dark:text-gray-300">Carregando batalhas...</p>
    </div>

    <div v-else-if="error" class="p-4 bg-red-100 dark:bg-red-300 text-red-700 dark:text-red-900 rounded-md mb-6">
      {{ error }}
    </div>

    <div v-else>
      <div v-if="!batalhasPendentes || batalhasPendentes.length === 0" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md text-center">
        <p class="text-lg text-gray-600 dark:text-gray-300 mb-4">Não há batalhas pendentes no momento.</p>
        <router-link to="/torneio" class="btn-primary">
          Voltar para o Torneio
        </router-link>
      </div>

      <div v-else>
        <div v-if="!selectedBatalha" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md mb-6">
          <h2 class="text-xl font-semibold mb-4 dark:text-white">Selecione uma Batalha para Administrar</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div 
              v-for="batalha in batalhasPendentes" 
              :key="batalha.id" 
              class="border dark:border-gray-700 rounded-md p-4 cursor-pointer hover:border-primary hover:bg-blue-50 dark:hover:bg-gray-700 transition-colors"
              @click="selectBatalha(batalha)"
            >
              <div class="flex justify-between items-center">
                <div>
                  <p class="font-semibold dark:text-white">{{ batalha.startup1.nome }}</p>
                  <p class="text-sm text-gray-500 dark:text-gray-400">{{ batalha.startup1.slogan }}</p>
                </div>
                <div class="text-center mx-2">
                  <span class="text-lg font-bold text-gray-500 dark:text-gray-300">VS</span>
                </div>
                <div class="text-right">
                  <p class="font-semibold dark:text-white">{{ batalha.startup2.nome }}</p>
                  <p class="text-sm text-gray-500 dark:text-gray-400">{{ batalha.startup2.slogan }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div v-else class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
          <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold dark:text-white">Gerenciando Batalha</h2>
            <button 
              @click="selectedBatalha = null" 
              class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300"
            >
              Voltar para lista
            </button>
          </div>

          <div class="flex flex-col md:flex-row justify-between gap-6 mb-8">
            <div class="flex-1 border dark:border-gray-700 rounded-lg p-4">
              <h3 class="font-bold text-lg mb-2 dark:text-white">{{ selectedBatalha.startup1.nome }}</h3>
              <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">{{ selectedBatalha.startup1.slogan }}</p>
              
              <div class="mb-2 font-semibold dark:text-white">Pontuação: {{ pontuacao.startup1 }}</div>
              
              <div class="mt-4 bg-gray-50 dark:bg-gray-700 p-3 rounded">
                <h4 class="font-semibold mb-2 dark:text-white">Eventos</h4>
                
                <div class="space-y-2">
                  <div class="flex items-center">
                    <input 
                      type="checkbox" 
                      id="s1-pitch" 
                      v-model="eventos.startup1.pitch_convincente"
                      class="mr-2"
                    />
                    <label for="s1-pitch" class="text-sm dark:text-gray-300">Pitch convincente (+6)</label>
                  </div>
                  
                  <div class="flex items-center">
                    <input 
                      type="checkbox" 
                      id="s1-bugs" 
                      v-model="eventos.startup1.produto_com_bugs"
                      class="mr-2"
                    />
                    <label for="s1-bugs" class="text-sm dark:text-gray-300">Produto com bugs (-4)</label>
                  </div>
                  
                  <div class="flex items-center">
                    <input 
                      type="checkbox" 
                      id="s1-tracao" 
                      v-model="eventos.startup1.boa_tracao"
                      class="mr-2"
                    />
                    <label for="s1-tracao" class="text-sm dark:text-gray-300">Boa tração (+3)</label>
                  </div>
                  
                  <div class="flex items-center">
                    <input 
                      type="checkbox" 
                      id="s1-investidor" 
                      v-model="eventos.startup1.investidor_irritado"
                      class="mr-2"
                    />
                    <label for="s1-investidor" class="text-sm dark:text-gray-300">Investidor irritado (-6)</label>
                  </div>
                  
                  <div class="flex items-center">
                    <input 
                      type="checkbox" 
                      id="s1-fake" 
                      v-model="eventos.startup1.fake_news"
                      class="mr-2"
                    />
                    <label for="s1-fake" class="text-sm dark:text-gray-300">Fake news (-8)</label>
                  </div>
                </div>
              </div>
            </div>

            <div class="flex-1 border dark:border-gray-700 rounded-lg p-4">
              <h3 class="font-bold text-lg mb-2 dark:text-white">{{ selectedBatalha.startup2.nome }}</h3>
              <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">{{ selectedBatalha.startup2.slogan }}</p>
              
              <div class="mb-2 font-semibold dark:text-white">Pontuação: {{ pontuacao.startup2 }}</div>
              
              <div class="mt-4 bg-gray-50 dark:bg-gray-700 p-3 rounded">
                <h4 class="font-semibold mb-2 dark:text-white">Eventos</h4>
                
                <div class="space-y-2">
                  <div class="flex items-center">
                    <input 
                      type="checkbox" 
                      id="s2-pitch" 
                      v-model="eventos.startup2.pitch_convincente"
                      class="mr-2"
                    />
                    <label for="s2-pitch" class="text-sm dark:text-gray-300">Pitch convincente (+6)</label>
                  </div>
                  
                  <div class="flex items-center">
                    <input 
                      type="checkbox" 
                      id="s2-bugs" 
                      v-model="eventos.startup2.produto_com_bugs"
                      class="mr-2"
                    />
                    <label for="s2-bugs" class="text-sm dark:text-gray-300">Produto com bugs (-4)</label>
                  </div>
                  
                  <div class="flex items-center">
                    <input 
                      type="checkbox" 
                      id="s2-tracao" 
                      v-model="eventos.startup2.boa_tracao"
                      class="mr-2"
                    />
                    <label for="s2-tracao" class="text-sm dark:text-gray-300">Boa tração (+3)</label>
                  </div>
                  
                  <div class="flex items-center">
                    <input 
                      type="checkbox" 
                      id="s2-investidor" 
                      v-model="eventos.startup2.investidor_irritado"
                      class="mr-2"
                    />
                    <label for="s2-investidor" class="text-sm dark:text-gray-300">Investidor irritado (-6)</label>
                  </div>
                  
                  <div class="flex items-center">
                    <input 
                      type="checkbox" 
                      id="s2-fake" 
                      v-model="eventos.startup2.fake_news"
                      class="mr-2"
                    />
                    <label for="s2-fake" class="text-sm dark:text-gray-300">Fake news (-8)</label>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="mt-6 text-center">
            <div v-if="pontuacao.startup1 === pontuacao.startup2" class="mb-4 p-3 bg-yellow-100 dark:bg-yellow-800 text-yellow-800 dark:text-yellow-100 rounded">
              Atenção: As pontuações estão empatadas! O sistema realizará um Shark Fight automaticamente ao resolver a batalha.
            </div>
            <button @click="resolverBatalha" :disabled="processandoBatalha" class="btn-primary px-6 py-2" :class="{ 'opacity-50 cursor-not-allowed': processandoBatalha }">
              <span v-if="processandoBatalha">Processando...</span>
              <span v-else>Resolver Batalha</span>
            </button>
          </div>
        </div>

        <div v-if="vencedorBatalha" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md mt-6 text-center animate-fadeIn">
          <h2 class="text-xl font-bold mb-4 dark:text-white">Resultado da Batalha</h2>
          <div class="mb-6">
            <p class="text-lg dark:text-gray-300">Vencedor:</p>
            <p class="text-2xl font-bold text-primary mt-2">{{ vencedorBatalha.nome }}</p>
            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ vencedorBatalha.slogan }}</p>
            <p class="text-green-600 dark:text-green-400 font-semibold mt-3">+30 pontos adicionados à pontuação!</p>
          </div>
          <button 
            @click="fecharResultadoBatalha" 
            class="btn-primary px-6 py-2"
          >
            Continuar
          </button>
        </div>
      </div>

      <div v-if="mostrarVencedor" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md mt-6 text-center animate-fadeIn">
        <h2 class="text-2xl font-bold mb-4 dark:text-white">Torneio Finalizado!</h2>
        <div class="mb-6">
          <p class="text-xl dark:text-gray-300">O vencedor é:</p>
          <p class="text-3xl font-bold text-primary mt-2">{{ vencedor?.nome || 'Sem nome' }}</p>
          <p class="text-gray-600 dark:text-gray-400 mt-1">{{ vencedor?.slogan || 'Sem slogan' }}</p>
        </div>
        <button 
          @click="navegarParaRelatorios" 
          class="btn-primary px-6 py-2"
        >
          Ver Relatórios
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, reactive, watch } from 'vue'
import { useTorneioStore } from '../stores/torneio'
import { useRouter } from 'vue-router'

const torneioStore = useTorneioStore()
const router = useRouter()

const loading = computed(() => torneioStore.loading)
const error = computed(() => torneioStore.error)
const batalhasPendentes = computed(() => torneioStore.batalhasPendentes)
const selectedBatalha = ref(null)
const processandoBatalha = ref(false)
const mostrarVencedor = ref(false)
const vencedor = ref(null)
const vencedorBatalha = ref(null)

const eventos = reactive({
  startup1: {
    pitch_convincente: false,
    produto_com_bugs: false,
    boa_tracao: false,
    investidor_irritado: false,
    fake_news: false
  },
  startup2: {
    pitch_convincente: false,
    produto_com_bugs: false,
    boa_tracao: false,
    investidor_irritado: false,
    fake_news: false
  }
})

const pontuacao = computed(() => {
  if (!selectedBatalha.value) return { startup1: 0, startup2: 0 }
  
  let pts1 = 70
  let pts2 = 70

  if (eventos.startup1.pitch_convincente) pts1 += 6
  if (eventos.startup1.produto_com_bugs) pts1 -= 4
  if (eventos.startup1.boa_tracao) pts1 += 3
  if (eventos.startup1.investidor_irritado) pts1 -= 6
  if (eventos.startup1.fake_news) pts1 -= 8

  if (eventos.startup2.pitch_convincente) pts2 += 6
  if (eventos.startup2.produto_com_bugs) pts2 -= 4
  if (eventos.startup2.boa_tracao) pts2 += 3
  if (eventos.startup2.investidor_irritado) pts2 -= 6
  if (eventos.startup2.fake_news) pts2 -= 8

  return { startup1: pts1, startup2: pts2 }
})

onMounted(async () => {
  console.log('Componente montado')
  try {
    await torneioStore.fetchStatus()
    console.log('Status do torneio:', torneioStore.status)
    
    if (torneioStore.status?.torneio_finalizado && torneioStore.status?.vencedora) {
      exibirVencedor(torneioStore.status.vencedora)
      return
    }
    
    await torneioStore.fetchBatalhasPendentes()
    console.log('Batalhas pendentes:', torneioStore.batalhasPendentes)
  } catch (err) {
    console.error('Erro na inicialização do componente:', err)
  }
})

watch(batalhasPendentes, (newBatalhas) => {
  if (newBatalhas && newBatalhas.length === 0) {
    selectedBatalha.value = null
  }
})

watch(() => torneioStore.status, async (newStatus, oldStatus) => {
  console.log('Status do torneio alterado:', newStatus)
  
  if (newStatus?.finalizado) {
    exibirVencedor(newStatus.vencedora)
    return
  }
  
  if (newStatus?.batalhas_pendentes === 0 && !newStatus?.finalizado) {
    try {
      console.log('Avançando torneio...')
      const resultado = await torneioStore.avancarTorneio()
      console.log('Resultado do avanço:', resultado)
      
      if (resultado?.finalizado) {
        exibirVencedor(resultado.vencedora)
      } else {
        await torneioStore.fetchBatalhasPendentes()
      }
    } catch (err) {
      console.error("Erro ao avançar torneio:", err)
      await torneioStore.fetchStatus()
    }
  }
}, { deep: true })

const selectBatalha = (batalha) => {
  selectedBatalha.value = batalha
  Object.keys(eventos.startup1).forEach(key => {
    eventos.startup1[key] = false
    eventos.startup2[key] = false
  })
  vencedorBatalha.value = null
}

const exibirVencedor = (winner) => {
  console.log('Exibindo vencedor:', winner)
  vencedor.value = winner
  mostrarVencedor.value = true
}

const navegarParaRelatorios = () => {
  console.log('Navegando para relatórios')
  try {
    router.push('/relatorios')
  } catch (e) {
    console.error('Erro ao navegar para relatórios:', e)
    router.push('/')
  }
}

const fecharResultadoBatalha = () => {
  vencedorBatalha.value = null
  selectedBatalha.value = null
}

const resolverBatalha = async () => {
  if (!selectedBatalha.value) return
  
  processandoBatalha.value = true
  
  const eventosFormatados = {
    startup1_pitch: eventos.startup1.pitch_convincente,
    startup1_bugs: eventos.startup1.produto_com_bugs,
    startup1_tracao: eventos.startup1.boa_tracao,
    startup1_investidor: eventos.startup1.investidor_irritado,
    startup1_fake_news: eventos.startup1.fake_news,
    startup2_pitch: eventos.startup2.pitch_convincente,
    startup2_bugs: eventos.startup2.produto_com_bugs,
    startup2_tracao: eventos.startup2.boa_tracao,
    startup2_investidor: eventos.startup2.investidor_irritado,
    startup2_fake_news: eventos.startup2.fake_news,
  }

  try {
    const resultado = await torneioStore.resolverBatalha(selectedBatalha.value.id, eventosFormatados)
    console.log('Resultado da resolução da batalha:', resultado)
    
    if (pontuacao.value.startup1 > pontuacao.value.startup2) {
      vencedorBatalha.value = selectedBatalha.value.startup1
    } else if (pontuacao.value.startup2 > pontuacao.value.startup1) {
      vencedorBatalha.value = selectedBatalha.value.startup2
    } else if (resultado?.vencedora) {
      vencedorBatalha.value = resultado.vencedora === selectedBatalha.value.startup1.id 
        ? selectedBatalha.value.startup1 
        : selectedBatalha.value.startup2
    }
    
    
    await torneioStore.fetchStatus()
    
    if (!torneioStore.status?.torneio_finalizado) {
      await torneioStore.fetchBatalhasPendentes()
    }
  } catch (e) {
    console.error('Erro ao resolver batalha:', e)
  } finally {
    processandoBatalha.value = false
  }
}
</script>

<style scoped>
.animate-fadeIn {
  animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

.spinner {
  border: 4px solid rgba(0, 0, 0, 0.1);
  width: 36px;
  height: 36px;
  border-radius: 50%;
  border-left-color: #3b82f6;
  animation: spin 1s linear infinite;
  margin: 0 auto;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>