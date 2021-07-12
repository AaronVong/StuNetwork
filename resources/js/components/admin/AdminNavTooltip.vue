<template>
    <div
        v-show="this.visible"
        class="tooltip w-36 absolute top-1/2 bg-gray-200 text-md text-black"
    >
        {{ this.text }}
    </div>
</template>

<script>
export default {
    name: "AdminNavTooltip",
    props: {
        text: {
            type: String,
            default: "tooltip",
        },
        visible: {
            type: Boolean,
            default: false,
        },
    },
};
</script>
