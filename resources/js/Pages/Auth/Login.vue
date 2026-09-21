<template>
    <AppLayout>
        <Head title="Cenotos | Iniciar sessão" />

        <main class="min-h-svh bg-slate-100 font-sans text-slate-900 antialiased sm:p-1">
            <div class="relative grid min-h-svh overflow-hidden bg-slate-950 sm:min-h-[calc(100svh-8px)] sm:rounded-[10px] lg:grid-cols-[minmax(0,1fr)_minmax(450px,510px)]">
                <div class="pointer-events-none absolute inset-0 overflow-hidden" aria-hidden="true">
                    <img class="login-background-slide absolute h-[108%] w-[108%] max-w-none" style="animation-delay: -3.84s" src="https://images.pexels.com/photos/35242813/pexels-photo-35242813.jpeg" alt="" decoding="async">
                    <img class="login-background-slide absolute h-[108%] w-[108%] max-w-none" style="animation-delay: 4.16s" src="https://images.pexels.com/photos/15890099/pexels-photo-15890099.jpeg" alt="" decoding="async">
                    <img class="login-background-slide absolute h-[108%] w-[108%] max-w-none" style="animation-delay: 12.16s" src="https://images.pexels.com/photos/18431220/pexels-photo-18431220.jpeg" alt="" decoding="async">
                    <img class="login-background-slide absolute h-[108%] w-[108%] max-w-none" style="animation-delay: 20.16s" src="https://images.pexels.com/photos/13069638/pexels-photo-13069638.jpeg" alt="" decoding="async">
                    <div class="absolute inset-0 z-10 bg-black/20" />
                </div>

                <section class="relative z-10 flex min-h-[28vh] items-start px-6 pb-14 pt-9 text-white lg:min-h-0 lg:items-center lg:p-[clamp(2rem,7vw,7rem)]" aria-label="Cenotos">
                    <div class="max-w-[340px] drop-shadow-[0_2px_18px_rgba(0,25,30,.24)]">
                        <div class="mb-5 flex items-center gap-4">
                            <div class="grid size-13 place-items-center rounded-xl border border-white/30 bg-slate-950/80 text-[1.7rem] font-extrabold tracking-[-.1em] shadow-[0_12px_25px_rgba(0,20,28,.16)]" aria-hidden="true">C</div>
                            <p class="m-0 text-[clamp(2rem,4vw,2.75rem)] font-bold tracking-[-.055em]">Cenotos</p>
                        </div>
                        <p class="m-0 hidden max-w-sm text-base leading-relaxed text-white/90 lg:block">Gestão centralizada para a sua empresa, num único lugar.</p>
                    </div>
                </section>

                <section class="relative z-10 flex items-start justify-center px-1 pb-8 lg:-translate-x-40 lg:items-center lg:p-[clamp(1.5rem,4vw,0rem)]" aria-labelledby="login-title">
                    <div class="w-full max-w-[450px] rounded-[7px] bg-white p-8 shadow-[0_24px_60px_rgba(5,27,40,.22)] lg:p-[clamp(2rem,4vw,3.5rem)]">
                        <p class="mb-3 text-xs font-bold tracking-[.12em] text-orange-600 uppercase">Cenotos</p>
                        <h1 id="login-title" class="m-0 text-[clamp(2rem,3vw,2.45rem)] font-bold leading-[1.08] tracking-[-.055em] text-slate-800">Iniciar sessão</h1>
                        <p class="mb-8 mt-3 text-[.96rem] leading-relaxed text-slate-500">Introduza as suas credenciais para aceder à sua área de trabalho.</p>

                        <form @submit.prevent="submit">
                            <Input v-model="form.username" label="Utilizador" name="username" autocomplete="username" autofocus :error="form.errors.username" />
                            <Input v-model="form.password" class="mt-5" label="Palavra-passe" name="password" type="password" autocomplete="current-password" :error="form.errors.password" />
                            <div class="mt-6 sm:mt-8 sm:flex sm:justify-end">
                                <Button type="submit" :disabled="form.processing" class="min-h-11 w-full cursor-pointer rounded-lg border border-orange-700 bg-orange-600 px-4 py-2 text-sm font-bold text-white transition hover:-translate-y-px hover:bg-orange-600/90 focus-visible:ring-3 focus-visible:ring-orange-500/30 focus-visible:ring-offset-3 focus-visible:outline-none disabled:cursor-wait disabled:opacity-70 sm:w-auto">Entrar</Button>
                            </div>
                        </form>
                    </div>
                </section>
            </div>
        </main>
    </AppLayout>
</template>

<script>
import { Head, useForm } from '@inertiajs/vue3';
import Button from '../../Components/Elements/Button.vue';
import Input from '../../Components/Elements/Input.vue';
import AppLayout from '../Layouts/App.vue';

export default {
    name: 'AuthLogin',
    components: { AppLayout, Button, Head, Input },

    data() {
        return {
            form: useForm({ username: '', password: '' }),
        };
    },

    methods: {
        submit() {
            this.form.post('/login', {
                onFinish: () => this.form.reset('password'),
            });
        },
    },
};
</script>

<style scoped>
.login-background-slide { 
    animation: login-background-slide 32s infinite; 
    inset: -4%; 
    object-fit: cover; 
    opacity: 0; 
    transform: translate3d(3%, 0, 0) scale(1.06); 
    will-change: opacity, transform; 
}
@keyframes login-background-slide {
    0% { 
        opacity: 0; 
        transform: translate3d(3%, 0, 0) scale(1.06); 
        z-index: 2; 
        animation-timing-function: cubic-bezier(.45, 0, .15, 1);
    }
    12% { 
        opacity: 1; 
        transform: translate3d(0, 0, 0) scale(1.04); 
        animation-timing-function: linear; 
    } 
    25% { 
        opacity: 1; 
        transform: translate3d(-1%, -.5%, 0) scale(1.03); 
        animation-timing-function: cubic-bezier(.85, 0, .55, 1); 
    } 
    37% { 
        opacity: 0; 
        transform: translate3d(-3%, -.5%, 0) scale(1.04); 
        z-index: 2; 
    } 
    37.01%, 100% { 
        opacity: 0; 
        transform: translate3d(3%, 0, 0) scale(1.06); 
        z-index: 1; 
    } 
}
@media (prefers-reduced-motion: reduce) { 
    .login-background-slide { 
        animation: none; 
        opacity: .9; 
        transform: scale(1.04); 
    } 
    .login-background-slide:not(:first-child) { 
        display: none; 
    } 
}
</style>
