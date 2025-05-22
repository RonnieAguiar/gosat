<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>

    <Head title="Consulta">
    </Head>
    <div
        class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] dark:bg-[#0a0a0a] lg:justify-center lg:p-8">
        <header class="not-has-[nav]:hidden mb-6 w-full max-w-[335px] text-sm lg:max-w-4xl">
            <nav class="flex items-center justify-end gap-4">
                <Link :href="route('home')"
                    class="inline-block  px-5 py-1.5 text-sm leading-normal text-[#1b1b18]">
                Consultas
                </Link>
                <Link :href="route('graficos')"
                    class="inline-block px-5 py-1.5 text-sm leading-normal text-[#1b1b18]">
                Gráficos
                </Link>
            </nav>
        </header>
        <div
            class="duration-750 starting:opacity-0 flex w-full items-center justify-center opacity-100 transition-opacity lg:grow">
            <main class="flex w-full max-w-[335px] flex-col-reverse overflow-hidden rounded-lg lg:max-w-4xl lg:flex-row">
                <div>
                    <label>Insira o CPF</label>
                    <input type="text" name="cpf" placeholder="CPF" >
                </div>
            </main>
        </div>
        <div class="h-14.5 hidden lg:block"></div>
    </div>
</template>