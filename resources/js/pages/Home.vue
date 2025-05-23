<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const cpf = ref('');
const valor = ref('');

interface Proposta {
  instituicaoFinanceira: string;
  modalidadeCredito: string;
  valorRequisitado: number;
  qntParcelas: number;
  taxaJuros: number;
  valorAPagar: number;
}

const resultado = ref<Proposta[]>([]);
const carregando = ref(false);
const erro = ref('');

const consultar = async () => {
  carregando.value = true;
  erro.value = '';
  resultado.value = [];

  try {
    const response = await fetch('/api/consulta_credito', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: JSON.stringify({
        cpf: cpf.value,
        valor: valor.value,
      }),
    });

    const data = await response.json();

    if (!response.ok) {
      erro.value = data.erro || 'Erro ao consultar.';
      return;
    }

    resultado.value = data.melhores_ofertas || [];
  } catch (e: any) {
    erro.value = `Erro na comunicação com o servidor. ${e.message}`;
  } finally {
    carregando.value = false;
  }
};
</script>


<template>
  <Head title="Consulta"></Head>

  <div class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] dark:bg-[#0a0a0a] lg:justify-center lg:p-8">
    <header class="mb-6 w-full max-w-4xl text-sm">
      <nav class="flex justify-end gap-4">
        <Link :href="route('home')" class="px-5 py-1.5 text-[#1b1b18]">Consultas</Link>
        <Link :href="route('graficos')" class="px-5 py-1.5 text-[#1b1b18]">Gráficos</Link>
      </nav>
    </header>

    <main class="w-full max-w-4xl bg-white p-6 rounded-xl shadow-md space-y-6 dark:bg-[#1b1b18]">
      <h1 class="text-xl font-semibold">Simulação de Crédito</h1>

      <form @submit.prevent="consultar" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
          <label class="block text-sm font-medium mb-1">CPF</label>
          <input v-model="cpf" type="text" placeholder="CPF" maxlength="11" class="w-full rounded-lg border px-4 py-2" />
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Valor desejado</label>
          <input v-model="valor" type="number" min="0" step="0.01" placeholder="1000" class="w-full rounded-lg border px-4 py-2"/>
        </div>
        <div class="sm:col-span-2 text-right">
          <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            {{ carregando ? 'Consultando...' : 'Consultar' }}
          </button>
        </div>
      </form>

      <div v-if="erro" class="text-red-600 text-sm">{{ erro }}</div>

      <div v-if="resultado.length" class="grid gap-4 sm:grid-cols-2 mt-4">
        <div
          v-for="(proposta, index) in resultado"
          :key="index"
          class="p-4 border rounded-lg shadow dark:bg-[#151515]"
        >
          <h2 class="font-semibold text-lg mb-2">Proposta {{ index + 1 }}</h2>
          <p><strong>Instituição:</strong> {{ proposta.instituicaoFinanceira }}</p>
          <p><strong>Modalidade:</strong> {{ proposta.modalidadeCredito }}</p>
          <p><strong>Juros:</strong> {{ proposta.taxaJuros }}%</p>
          <p><strong>Valor Requisitado:</strong> R$ {{ proposta.valorRequisitado.toLocaleString('pt-BR') }}</p>
          <p><strong>Parcelas:</strong> {{ proposta.qntParcelas }}</p>
          <p><strong>Total a Pagar:</strong> R$ {{ proposta.valorAPagar.toLocaleString('pt-BR') }}</p>
        </div>
      </div>

      <div v-else-if="!carregando && resultado.length === 0" class="text-gray-500">Nenhuma proposta encontrada.</div>
    </main>
  </div>
</template>
