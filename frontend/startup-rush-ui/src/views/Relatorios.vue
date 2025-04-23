<template>
  <div>
    <h1 class="text-3xl font-bold mb-6 dark:text-white">Resultado Final do Torneio</h1>
    
    <div v-if="loading" class="text-center py-4">
      <div class="spinner"></div>
      <p class="mt-2 text-gray-600 dark:text-gray-300">Carregando resultados...</p>
    </div>
    
    <div v-else-if="error" class="p-4 bg-red-100 dark:bg-red-300 text-red-700 dark:text-red-900 rounded-md mb-6">
      {{ error }}
    </div>
    
    <div v-else-if="!relatorio || !relatorio.startups || relatorio.startups.length === 0" 
         class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md text-center">
      <p class="text-lg text-gray-600 dark:text-gray-300">Não foi possível encontrar resultados para o torneio.</p>
      <button @click="tentarNovamente" class="btn-primary mt-4">
        Tentar Novamente
      </button>
    </div>
    
    <div v-else>
      <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md mb-6">
        <h2 class="text-xl font-bold mb-4 dark:text-white">Classificação Geral</h2>
        
        <div class="overflow-x-auto">
          <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
            <thead>
              <tr class="bg-gray-100 dark:bg-gray-700">
                <th class="py-2 px-4 border-b dark:border-gray-600 text-left dark:text-gray-300">Posição</th>
                <th class="py-2 px-4 border-b dark:border-gray-600 text-left dark:text-gray-300">Startup</th>
                <th class="py-2 px-4 border-b dark:border-gray-600 text-center dark:text-gray-300">Pontuação</th>
                <th class="py-2 px-4 border-b dark:border-gray-600 text-center dark:text-gray-300">Pitches</th>
                <th class="py-2 px-4 border-b dark:border-gray-600 text-center dark:text-gray-300">Bugs</th>
                <th class="py-2 px-4 border-b dark:border-gray-600 text-center dark:text-gray-300">Trações</th>
                <th class="py-2 px-4 border-b dark:border-gray-600 text-center dark:text-gray-300">Investidores Irritados</th>
                <th class="py-2 px-4 border-b dark:border-gray-600 text-center dark:text-gray-300">Fake News</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(startup, index) in relatorio.startups" :key="startup.id" 
                  :class="{'bg-green-50 dark:bg-green-900': index === 0}">
                <td class="py-2 px-4 border-b dark:border-gray-600 dark:text-gray-300">{{ index + 1 }}</td>
                <td class="py-2 px-4 border-b dark:border-gray-600 font-medium dark:text-white">{{ startup.nome }}</td>
                <td class="py-2 px-4 border-b dark:border-gray-600 text-center font-bold dark:text-white">{{ startup.pontos }}</td>
                <td class="py-2 px-4 border-b dark:border-gray-600 text-center dark:text-gray-300">{{ startup.num_pitches }}</td>
                <td class="py-2 px-4 border-b dark:border-gray-600 text-center dark:text-gray-300">{{ startup.num_bugs }}</td>
                <td class="py-2 px-4 border-b dark:border-gray-600 text-center dark:text-gray-300">{{ startup.num_tracoes }}</td>
                <td class="py-2 px-4 border-b dark:border-gray-600 text-center dark:text-gray-300">{{ startup.num_investidores_irritados }}</td>
                <td class="py-2 px-4 border-b dark:border-gray-600 text-center dark:text-gray-300">{{ startup.num_fake_news }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      
      <div v-if="relatorio.campeao" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md mb-6 text-center">
        <h2 class="text-2xl font-bold text-green-600 dark:text-green-400 mb-4">🏆 CAMPEÃO DO TORNEIO! 🏆</h2>
        <div class="p-8">
          <h3 class="text-2xl font-bold dark:text-white">{{ relatorio.campeao.nome }}</h3>
          <p class="text-gray-600 dark:text-gray-400 italic mt-2">{{ relatorio.campeao.slogan }}</p>
        </div>
      </div>
      
      <div class="flex justify-center gap-4 mt-6">
        <button @click="voltarParaInicio" class="btn-secondary">
          Voltar para o Início
        </button>
        <button @click="iniciarNovoTorneio" class="btn-primary">
          Reiniciar Torneio
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '../services/api'

const router = useRouter()
const relatorio = ref(null)
const loading = ref(true)
const error = ref(null)
const baixarPDF = async () => {
  try {
    loading.value = true;
    window.open('http://localhost:8012/api/relatorio/pdf', '_blank');
    loading.value = false;
  } catch (err) {
    error.value = "Erro ao baixar PDF: " + (err.message || "Erro desconhecido");
    loading.value = false;
  }
}

const carregarRelatorio = async () => {
  try {
    loading.value = true;
    error.value = null;
    console.log("Carregando relatório detalhado");
    
    const response = await api.getRelatorioDetalhado();
    console.log("Relatório carregado:", response.data);
    
    if (!response.data) {
      error.value = "Resposta da API inválida.";
      loading.value = false;
      return;
    }
    
    if (response.data.error) {
      error.value = response.data.error;
      loading.value = false;
      return;
    }
    
    if (!Array.isArray(response.data.startups)) {
      error.value = "Formato de dados inválido: startups não é um array";
      loading.value = false;
      return;
    }
    
    relatorio.value = response.data;
    
    if (!relatorio.value.campeao && relatorio.value.startups && relatorio.value.startups.length > 0) {
      relatorio.value.campeao = relatorio.value.startups[0];
      console.log("Campeão determinado pelo frontend:", relatorio.value.campeao);
    }
    
  } catch (err) {
    console.error("Erro ao carregar relatório:", err);
    error.value = "Erro ao carregar relatório: " + (err.message || "Erro desconhecido");
  } finally {
    loading.value = false;
  }
};

onMounted(carregarRelatorio)

const tentarNovamente = () => {
  carregarRelatorio()
}

const voltarParaInicio = () => {
  router.push('/')
}

const iniciarNovoTorneio = async () => {
  try {
    loading.value = true
    await api.resetarTorneio()
    router.push('/torneio')
  } catch (err) {
    error.value = "Erro ao resetar torneio: " + (err.message || "Erro desconhecido")
    loading.value = false
  }
}
</script>