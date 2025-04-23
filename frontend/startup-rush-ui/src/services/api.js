import axios from 'axios'

const instance = axios.create({
  baseURL: 'http://localhost:8012',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  }
})

instance.interceptors.response.use(
  response => response,
  error => {
    if (error.response) {
      console.error('API Error Response:', error.response.data)
    } else if (error.request) {
      console.error('API No Response:', error.request)
    } else {
      console.error('API Request Error:', error.message)
    }
    return Promise.reject(error)
  }
)

const api = {
  getStartups() {
    return instance.get('/startups')
  },
  createStartup(startup) {
    return instance.post('/startups', startup)
  },
  
  iniciarTorneio() {
    return instance.post('/torneio/iniciar')
  },
  avancarTorneio(params) {
    return instance.post('/torneio/avancar', params)
  },
  getStatusTorneio() {
    return instance.get('/torneio/status')
  },
  resetarTorneio() {
    return instance.post('/torneio/resetar')
  },
  
  getBatalhasPendentes() {
    return instance.get('/batalhas/pendentes')
  },
  resolverBatalha(id, dados) {
    return instance.post(`/batalhas/${id}/resolver`, dados)
  },
  
  getRelatorioDetalhado() {
    return instance.get('/torneio/relatorio-detalhado')
  },
  getRelatorioPDF() {
    return instance.get('/torneio/relatorio/pdf', { responseType: 'blob' })
  }
}

export default api