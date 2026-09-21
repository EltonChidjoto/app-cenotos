<template>
    <div :class="['rounded-md border px-4 py-3 text-sm leading-relaxed', colorClasses]" role="alert">
        <slot />
    </div>
</template>

<script>
export default {
    name: 'MessageAlert',

    props: {
        color: {
            type: String,
            default: 'red',
        },
    },

    computed: {
        colorClasses() {
            const colors = {
                red: 'border-red-200 bg-red-50 text-red-700',
                green: 'border-green-200 bg-green-50 text-green-700',
                blue: 'border-blue-200 bg-blue-50 text-blue-700',
                yellow: 'border-yellow-200 bg-yellow-50 text-yellow-700',
                gray: 'border-slate-200 bg-slate-50 text-slate-700',
            };

            return colors[this.color] ?? colors.red;
        },
    },
};
</script>
