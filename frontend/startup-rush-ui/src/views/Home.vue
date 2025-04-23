<template>
  <div>
    <h1 class="text-3xl font-bold mb-6 text-gray-900 dark:text-white">Bem-vindo ao Startup Rush</h1>
    <p class="mb-4 text-gray-700 dark:text-gray-300">
      O Startup Rush é um torneio entre pequenas startups que competem por reconhecimento e destaque no mercado.
    </p>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
      <div class="p-6 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold mb-4">Cadastre suas Startups</h2>
        <p class="mb-4">Comece cadastrando entre 4 e 8 startups para participar do torneio.</p>
        <router-link to="/startups" class="btn-primary inline-block">
          Cadastrar Startups
        </router-link>
      </div>
      <div class="p-6 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold mb-4">Iniciar Torneio</h2>
        <p class="mb-4">Configure e inicie um novo torneio entre as startups cadastradas.</p>
        <router-link to="/torneio" class="btn-secondary inline-block">
          Iniciar Torneio
        </router-link>
      </div>
    </div>
  </div>
</template>
