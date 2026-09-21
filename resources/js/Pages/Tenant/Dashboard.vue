<template>
    <AppLayout>
        <Head title="Cenotos | Dashboard" />

        <main class="dashboard">
            <div class="wrap">
                <section class="hero">
                    <div>
                        <span class="badge">Tenant ativo</span>
                        <h1>{{ tenant.name }}</h1>
                        <p class="muted">Base em uso: <strong>{{ tenant.databaseName ?? 'n/a' }}</strong></p>
                    </div>
                    <button class="button" type="button" @click="logout">Sair</button>
                </section>

                <section class="stats">
                    <div class="stat"><span class="muted">Vendas</span><strong>{{ salesTotal }}</strong></div>
                    <div class="stat"><span class="muted">Volume</span><strong>{{ formatAmount(salesAmount) }}</strong></div>
                    <div class="stat"><span class="muted">Cache</span><strong>{{ cacheInfo.store }}</strong></div>
                </section>

                <section class="panel">
                    <div class="topline">
                        <div>
                            <h2>Últimas vendas</h2>
                            <p class="muted">Dados cacheados por tenant e por página. A primeira leitura vem do MySQL; as seguintes, do Redis.</p>
                        </div>
                        <div>
                            <span class="badge">page {{ cacheInfo.page }}</span>
                            <span class="badge">key {{ cacheInfo.key }}</span>
                        </div>
                    </div>

                    <table>
                        <thead><tr><th>Referência</th><th>Cliente</th><th>Valor</th><th>Status</th></tr></thead>
                        <tbody>
                            <tr v-for="sale in sales.data" :key="sale.id">
                                <td>{{ sale.reference }}</td><td>{{ sale.customer_name }}</td><td>{{ formatAmount(sale.amount) }}</td><td>{{ sale.status }}</td>
                            </tr>
                            <tr v-if="!sales.data.length"><td colspan="4" class="muted">Sem vendas registadas nesta empresa.</td></tr>
                        </tbody>
                    </table>

                    <nav v-if="sales.links?.length" class="pagination" aria-label="Paginação">
                        <template v-for="link in sales.links" :key="link.label">
                            <Link v-if="link.url" :href="link.url" class="button pagination-link" :class="{ active: link.active }" v-html="link.label" />
                            <span v-else class="button pagination-link disabled" v-html="link.label" />
                        </template>
                    </nav>
                </section>
            </div>
        </main>
    </AppLayout>
</template>

<script>
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '../Layouts/App.vue';

export default {
    name: 'TenantDashboard',
    components: { AppLayout, Head, Link },

    props: {
        tenant: { type: Object, required: true },
        sales: { type: Object, required: true },
        salesTotal: { type: Number, required: true },
        salesAmount: { type: Number, required: true },
        cacheInfo: { type: Object, required: true },
    },

    methods: {
        formatAmount(amount) {
            return new Intl.NumberFormat('pt-PT', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            }).format(amount);
        },

        logout() {
            router.post('/logout');
        },
    },
};
</script>

<style scoped>
.dashboard { min-height: 100svh; color: #edf4ff; background: radial-gradient(circle at top right, rgba(97, 218, 251, .16), transparent 28%), radial-gradient(circle at bottom left, rgba(79, 140, 255, .16), transparent 28%), linear-gradient(180deg, #050912, #0a1222 54%, #07111f); font-family: Inter, ui-sans-serif, system-ui, sans-serif; }
.wrap { width: min(1120px, calc(100vw - 2rem)); margin: 0 auto; padding: 2rem 0 3rem; }.hero, .panel { border: 1px solid rgba(148, 163, 184, .18); border-radius: 24px; background: rgba(11, 20, 36, .88); box-shadow: 0 20px 60px rgba(0, 0, 0, .28); backdrop-filter: blur(14px); }.hero { display: flex; align-items: end; justify-content: space-between; gap: 1rem; margin-bottom: 1.5rem; padding: 2rem; }
h1 { margin: 0 0 .5rem; font-size: clamp(1.8rem, 3vw, 3rem); } h2 { margin: 0; }.muted { color: #8ea2bf; }.stats { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }.stat, .panel { padding: 1.25rem; }.stat { border: 1px solid rgba(148, 163, 184, .18); border-radius: 20px; background: rgba(255, 255, 255, .03); }.stat strong { display: block; margin-top: .35rem; font-size: 1.7rem; }
table { width: 100%; border-collapse: collapse; } th, td { padding: .9rem 0; border-bottom: 1px solid rgba(148, 163, 184, .12); text-align: left; }.badge { display: inline-flex; margin-right: .35rem; padding: .25rem .6rem; border-radius: 999px; background: rgba(97, 218, 251, .12); color: #61dafb; font-size: .85rem; }.topline { display: flex; align-items: center; justify-content: space-between; gap: 1rem; margin-bottom: 1rem; }.topline p { margin: .25rem 0 0; }
.button { display: inline-flex; align-items: center; justify-content: center; padding: .75rem 1rem; border: 1px solid rgba(148, 163, 184, .18); border-radius: 14px; color: #edf4ff; background: rgba(255, 255, 255, .04); text-decoration: none; cursor: pointer; }.pagination { display: flex; flex-wrap: wrap; gap: .5rem; margin-top: 1rem; }.pagination-link { min-width: 2.6rem; }.pagination-link.active { border-color: #61dafb; color: #61dafb; }.pagination-link.disabled { cursor: not-allowed; opacity: .45; }
@media (max-width: 800px) { .hero, .topline { flex-direction: column; align-items: start; } .stats { grid-template-columns: 1fr; } }
</style>
