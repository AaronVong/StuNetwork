<template>
    <ul class="divide-y toastlist" style="overflow: auto" ref="toastList">
        <Toast
            v-for="(toast, index) in this.toastList"
            :key="index"
            :toast="toast"
            :owned="this.owner === toast.user.id"
            :liked="toast.likes.some((item) => item.user_id === this.owner)"
        ></Toast>
    </ul>
</template>
<script>
import Toast from "./Toast.vue";
import { mapGetters, mapActions, mapMutations } from "vuex";
export default {
    data() {
        return {
            loading: false,
        };
    },
    methods: {
        ...mapActions(["toastListPaginateAction"]),
        ...mapMutations(["setToastList"]),
    },
    props: {
        owner: Number,
        toast_list: { type: Array, default: null },
    },
    computed: {
        ...mapGetters(["toastList"]),
    },
    components: { Toast },
    mounted() {
        if (this.toast_list) {
            this.setToastList(this.toast_list);
        }
    },
};
</script>
