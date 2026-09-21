<template>
    <button :type="type">
        <slot />
    </button>
</template>

<script>
export default {
    name: 'ElementButton',

    props: {
        type: {
            type: String,
            default: 'button',
        },
    },
};
</script>
