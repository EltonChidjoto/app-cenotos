<template>
    <div>
        <label v-if="label" :for="inputId" :class="['mb-2 block text-sm font-semibold text-slate-700', labelClass]">{{ label }}</label>
        <textarea :id="inputId" :name="name" :rows="rows" :value="modelValue" :aria-invalid="Boolean(errorMessage)" :class="['block w-full rounded-md border bg-white px-3 py-2 text-slate-800 outline-none transition focus:ring-1', errorMessage ? 'border-red-500 focus:border-red-600 focus:ring-red-500/15' : 'border-slate-300 focus:border-orange-600 focus:ring-orange-500/15', inputClass]" @input="updateValue" />
        <p v-if="errorMessage" class="mt-2 text-sm text-red-700" role="alert">{{ errorMessage }}</p>
    </div>
</template>

<script>
export default {
    name: 'ElementTextarea',

    props: {
        id: { type: String, default: null },
        label: { type: String, default: null },
        name: { type: String, required: true },
        modelValue: { type: String, default: '' },
        rows: { type: [String, Number], default: 4 },
        error: { type: [String, Array], default: null },
        labelClass: { type: String, default: '' },
        inputClass: { type: String, default: '' },
    },

    emits: ['update:modelValue'],

    computed: {
        inputId() {
            return this.id ?? this.name;
        },

        errorMessage() {
            return Array.isArray(this.error) ? this.error[0] : this.error;
        },
    },

    methods: {
        updateValue(event) {
            this.$emit('update:modelValue', event.target.value);
        },
    },
};
</script>
