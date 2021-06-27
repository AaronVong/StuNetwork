<template></template>

<script>
export default {
    name: "ChatList",
    data() {
        return {
            switch: false,
        };
    },
    props: {
        chat_list: {
            type: Array,
            default: [],
        },
    },
    methods: {
        toggleChatList() {
            this.switch = !this.switch;
        },
    },
};
</script>
