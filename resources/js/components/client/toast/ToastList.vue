<template>
    <ul
        v-loading="this.loading"
        element-loading-text="Đang tải..."
        class="divide-y toastlist"
        @scroll="this.handleScroll"
        style="overflow: auto"
        ref="toastList"
    >
        <Toast
            v-for="(toast, index) in this.toastList"
            :key="index"
            :toast="toast"
            :owned="this.owner === toast.user.id"
            :liked="toast.likes.some((item) => item.user_id === this.owner)"
        ></Toast>
    </ul>
    <p v-if="loading" class="font-bold text-xl text-center">Đang tải...</p>
</template>
<script>
import Toast from "./Toast.vue";
import { mapGetters, mapActions } from "vuex";
export default {
    data() {
        return {
            loading: false,
        };
    },
    methods: {
        ...mapActions(["toastListPaginateAction"]),
        async loadMore() {
            this.loading = true;
            await this.toastListPaginateAction();
            this.loading = false;
        },
        handleScroll() {},
    },
    props: {
        owner: Number,
    },
    computed: {
        ...mapGetters(["toastList"]),
    },
    components: { Toast },
    async mounted() {
        await this.toastListPaginateAction();
    },
};
</script>
