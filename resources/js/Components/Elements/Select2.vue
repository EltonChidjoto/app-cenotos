<template>
    <Select v-bind="$props" @update:model-value="$emit('update:modelValue', $event)">
        <slot />
    </Select>
</template>

<script>
import Select from './Select.vue';

export default {
    name: 'ElementSelect2',

    components: { Select },

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
};
</script>
