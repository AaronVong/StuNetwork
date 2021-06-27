<template>
    <div class="infinite-list-wrapper border-b" style="overflow: auto">
        <ul
            v-infinite-scroll="this.load"
            infinite-scroll-disabled="disabled"
            class="divide-y list"
            ref="toastList"
        >
            <Toast
                v-for="(toast, index) in this.toastList"
                :key="index"
                :toast="toast"
                :owned="this.owner === toast.user.id"
                :liked="toast.liked == 1"
                :followed="toast.followed == 1"
            ></Toast>
        </ul>
        <p v-if="loading">Loading...</p>
    </div>
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
        ...mapActions([
            "toastListPaginateAction",
            "getProfilesFollowedByUserId",
        ]),
        ...mapMutations(["setToastList"]),
        load() {},
        disabled() {
            return this.loading;
        },
    },
    props: {
        owner: Number,
        toast_list: { type: Array, default: null },
    },
    computed: {
        ...mapGetters(["toastList", "followings"]),
    },
    components: { Toast },
    mounted() {
        // const getFollowings = async () => {
        //     // dành cho việc quick follow trong toast tools
        //     await this.getProfilesFollowedByUserId(this.owner);
        // };
        if (this.toast_list) {
            // khi vào trang home
            this.setToastList(this.toast_list);
            // getFollowings();
        }
    },
};
</script>
