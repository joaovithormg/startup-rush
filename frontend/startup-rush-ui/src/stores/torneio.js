import { defineStore } from 'pinia'
import api from '../services/api'

export const useTorneioStore = defineStore('torneio', {
  state: () => ({
    status: null,
    batalhasPendentes: [],
    loading: false,
    error: null
  }),
  
  actions: {
    async fetchStatus() {
      try {
        this.loading = true;
        this.error = null;
        const response = await api.getStatusTorneio();
        this.status = response.data;
        return response.data;
      } catch (error) {
        this.error = 'Erro ao carregar status do torneio';
        console.error('Erro ao carregar status do torneio:', error);
        throw error;
      } finally {
        this.loading = false;
      }
    },
    
    async iniciarTorneio() {
      try {
        this.loading = true;
        this.error = null;
        const response = await api.iniciarTorneio();
        this.status = response.data;
        return response.data;
      } catch (error) {
        this.error = 'Erro ao iniciar torneio';
        console.error('Erro ao iniciar torneio:', error);
        throw error;
      } finally {
        this.loading = false;
      }
    },
    
    async avancarTorneio() {
      try {
        this.loading = true;
        this.error = null;
        const response = await api.avancarTorneio();
        this.status = response.data;
        
        if (response.data && response.data.torneio_finalizado) {
          this.batalhasPendentes = [];
        }
        
        return response.data;
      } catch (error) {
        this.error = 'Erro ao avançar torneio';
        console.error('Erro ao avançar torneio:', error);
        
        try {
          await this.fetchStatus();
        } catch (e) {
        }
        
        throw error;
      } finally {
        this.loading = false;
      }
    },
    
    async resetarTorneio() {
      try {
        this.loading = true;
        this.error = null;
        const response = await api.resetarTorneio();
        this.status = null;
        this.batalhasPendentes = [];
        return response.data;
      } catch (error) {
        this.error = 'Erro ao resetar torneio';
        console.error('Erro ao resetar torneio:', error);
        throw error;
      } finally {
        this.loading = false;
      }
    },
    
    async fetchBatalhasPendentes() {
      try {
        this.loading = true;
        this.error = null;
        const response = await api.getBatalhasPendentes();
        this.batalhasPendentes = response.data;
        return response.data;
      } catch (error) {
        this.error = 'Erro ao buscar batalhas pendentes';
        console.error('Erro ao buscar batalhas pendentes:', error);
        throw error;
      } finally {
        this.loading = false;
      }
    },
    
    async resolverBatalha(id, dados) {
      try {
        this.loading = true;
        this.error = null;
        const response = await api.resolverBatalha(id, dados);
        this.batalhasPendentes = this.batalhasPendentes.filter(b => b.id !== id);
        return response.data;
      } catch (error) {
        this.error = 'Erro ao resolver batalha';
        console.error('Erro ao resolver batalha:', error);
        throw error;
      } finally {
        this.loading = false;
      }
    },
    
    async getRelatorioDetalhado() {
      try {
        this.loading = true;
        this.error = null;
        const response = await api.getRelatorioDetalhado();
        return response.data;
      } catch (error) {
        this.error = 'Erro ao carregar relatório detalhado';
        console.error('Erro ao carregar relatório detalhado:', error);
        throw error;
      } finally {
        this.loading = false;
      }
    },
    
    async getRelatorioPDF() {
      try {
        this.loading = true;
        this.error = null;
        const response = await api.getRelatorioPDF();
        return response.data;
      } catch (error) {
        this.error = 'Erro ao gerar PDF do relatório';
        console.error('Erro ao gerar PDF do relatório:', error);
        throw error;
      } finally {
        this.loading = false;
      }
    }
  }
})