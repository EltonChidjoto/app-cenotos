<template>
    <div>
        <label v-if="label" :for="inputId" :class="['mb-2 block text-sm font-semibold text-slate-700', labelClass]">
            {{ label }}
        </label>
        <input
            :id="inputId"
            :type="type"
            :name="name"
            :value="modelValue"
            :autocomplete="autocomplete"
            :autofocus="autofocus"
            :aria-invalid="Boolean(errorMessage)"
            :class="[
                'block min-h-[2.64rem] w-full rounded-md border bg-white px-3 py-2 text-slate-800 outline-none transition focus:ring-1',
                errorMessage ? 'border-red-500 focus:border-red-600 focus:ring-red-500/15 rounded-b-none' : 'border-slate-300 focus:border-orange-600 focus:ring-orange-500/15 rounded-b-md',
                inputClass,
            ]"
            @input="updateValue"
        >
        <Output :messages="error" :field="name" />
    </div>
</template>

<script>
import Output from '../Messages/Output.vue';

export default {
    name: 'ElementInput',

    components: { Output },

    props: {
        id: { type: String, default: null },
        label: { type: String, default: null },
        name: { type: String, required: true },
        type: { type: String, default: 'text' },
        modelValue: { type: [String, Number], default: '' },
        autocomplete: { type: String, default: null },
        autofocus: { type: Boolean, default: false },
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
