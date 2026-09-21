<template>
    <div v-if="outputMessages.length" :class="['rounded-b-md border border-t-0 px-3 py-1 text-sm leading-relaxed', colorClasses]" role="alert">
        <ul class="m-0 list-inside">
            <li v-for="message in outputMessages" :key="message">{{ message }}</li>
        </ul>
    </div>
</template>

<script>
export default {
    name: 'MessageOutput',

    props: {
        messages: {
            type: [String, Array],
            default: () => [],
        },
        errors: {
            type: Object,
            default: null,
        },
        field: {
            type: String,
            default: null,
        },
        color: {
            type: String,
            default: 'red',
        },
    },

    computed: {
        outputMessages() {
            const messages = this.field && this.errors
                ? this.fieldErrors
                : this.messages;

            if (Array.isArray(messages)) {
                return messages.filter(Boolean);
            }

            return messages ? [messages] : [];
        },

        fieldErrors() {
            if (typeof this.errors.get === 'function') {
                return this.errors.get(this.field);
            }

            return this.errors[this.field];
        },

        colorClasses() {
            const colors = {
                red: 'border-red-600 bg-red-50 text-red-700',
                green: 'border-green-600 bg-green-50 text-green-700',
                blue: 'border-blue-600 bg-blue-50 text-blue-700',
                yellow: 'border-yellow-600 bg-yellow-50 text-yellow-700',
                gray: 'border-slate-600 bg-slate-50 text-slate-700',
            };

            return colors[this.color] ?? colors.red;
        },
    },
};
</script>
