<template>
    <Input v-bind="$props" type="date" @update:model-value="$emit('update:modelValue', $event)" />
</template>

<script>
import Input from './Input.vue';

export default {
    name: 'ElementDatepicker',

    components: { Input },

    props: {
        id: { type: String, default: null },
        label: { type: String, default: null },
        name: { type: String, required: true },
        modelValue: { type: String, default: '' },
        error: { type: [String, Array], default: null },
        labelClass: { type: String, default: '' },
        inputClass: { type: String, default: '' },
    },

    emits: ['update:modelValue'],
};
</script>
