<template>
    <div>
        <label v-if="label" :for="inputId" :class="['mb-2 block text-sm font-semibold text-slate-700', labelClass]">{{ label }}</label>
        <select :id="inputId" :name="name" :value="modelValue" :multiple="multiple" :aria-invalid="Boolean(errorMessage)" :class="['block min-h-[2.64rem] w-full rounded-md border bg-white px-3 py-2 text-slate-800 outline-none transition focus:ring-1', errorMessage ? 'border-red-500 focus:border-red-600 focus:ring-red-500/15' : 'border-slate-300 focus:border-orange-600 focus:ring-orange-500/15', inputClass]" @change="updateValue">
            <option v-if="placeholder && !multiple" value="">{{ placeholder }}</option>
            <option v-for="option in options" :key="optionValueOf(option)" :value="optionValueOf(option)">{{ optionLabelOf(option) }}</option>
            <slot />
        </select>
        <p v-if="errorMessage" class="mt-2 text-sm text-red-700" role="alert">{{ errorMessage }}</p>
    </div>
</template>

<script>
export default {
    name: 'ElementSelect',

    props: {
        id: { type: String, default: null },
        label: { type: String, default: null },
        name: { type: String, required: true },
        modelValue: { type: [String, Number, Array], default: '' },
        options: { type: Array, default: () => [] },
        optionValue: { type: String, default: 'value' },
        optionLabel: { type: String, default: 'label' },
        placeholder: { type: String, default: null },
        multiple: { type: Boolean, default: false },
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
        optionValueOf(option) {
            return typeof option === 'object' ? option[this.optionValue] : option;
        },

        optionLabelOf(option) {
            return typeof option === 'object' ? option[this.optionLabel] : option;
        },

        updateValue(event) {
            const value = this.multiple
                ? Array.from(event.target.selectedOptions, (option) => option.value)
                : event.target.value;

            this.$emit('update:modelValue', value);
        },
    },
};
</script>
