<template>
    <div>
        <label :for="inputId" class="mb-2 block text-sm font-semibold text-slate-700">{{ label }}</label>
        <input :id="inputId" :name="name" type="file" :accept="accept" :multiple="multiple" :aria-invalid="Boolean(errorMessage)" :class="['block w-full rounded-md border bg-white px-3 py-2 text-sm text-slate-700 file:mr-3 file:rounded file:border-0 file:bg-orange-50 file:px-3 file:py-1 file:font-semibold file:text-orange-700', errorMessage ? 'border-red-500' : 'border-slate-300']" @change="updateFiles">
        <p v-if="files.length" class="mt-2 text-sm text-slate-500">{{ files.map((file) => file.name).join(', ') }}</p>
        <p v-if="errorMessage" class="mt-2 text-sm text-red-700" role="alert">{{ errorMessage }}</p>
    </div>
</template>

<script>
export default {
    name: 'ElementDropzone',

    props: {
        id: { type: String, default: null },
        label: { type: String, default: 'Ficheiros' },
        name: { type: String, required: true },
        modelValue: { type: [File, Array], default: null },
        accept: { type: String, default: null },
        multiple: { type: Boolean, default: false },
        error: { type: [String, Array], default: null },
    },

    emits: ['update:modelValue'],

    computed: {
        inputId() {
            return this.id ?? this.name;
        },

        files() {
            if (!this.modelValue) {
                return [];
            }

            return Array.isArray(this.modelValue) ? this.modelValue : [this.modelValue];
        },

        errorMessage() {
            return Array.isArray(this.error) ? this.error[0] : this.error;
        },
    },

    methods: {
        updateFiles(event) {
            const selected = Array.from(event.target.files ?? []);
            this.$emit('update:modelValue', this.multiple ? selected : (selected[0] ?? null));
        },
    },
};
</script>
