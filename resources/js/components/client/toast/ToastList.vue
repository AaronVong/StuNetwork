<template>
    <div class="border-b">
        <ul class="divide-y infinite-list-wrapper" style="overflow: auto">
            <Toast
                v-for="(toast, index) in this.toastList"
                :key="index"
                :toast="toast"
                :owned="this.owner === toast.user.id"
                :liked="toast.liked == 1"
                :followed="toast.followed == 1"
            ></Toast>
        </ul>
        <div v-if="this.loading">
            <img
                src="/images/Pulse-1s-200px.svg"
                class="w-full h-16 bg-transparent"
            />
        </div>
        <div v-if="this.noMore" class="text-lg text-center py-3 font-bold">
            Không còn toast để tải
        </div>
    </div>
</template>
<script>
import Toast from "./Toast.vue";
import { mapGetters, mapActions, mapMutations } from "vuex";
export default {
    data() {
        return {
            loading: false,
            noMore: false,
            count: 0,
        };
    },
    methods: {
        ...mapActions([
            "toastListPaginateAction",
            "getProfilesFollowedByUserId",
        ]),
        ...mapMutations(["setToastList"]),
        async loadMoreToast() {},
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
        if (this.toast_list) {
            this.setToastList(this.toast_list);
        }
        const regex = new RegExp("^(/toast)/[0-9]+$", "i");
        const result = regex.test(window.location.pathname);
        if (result) {
            // nếu đang trang chi tiết toast không chạy infinite scroll
            return;
        }
        window.addEventListener("scroll", async () => {
            const { scrollTop, scrollHeight, clientHeight } =
                document.documentElement;

            // console.log({ scrollTop, scrollHeight, clientHeight });

            if (clientHeight + scrollTop >= scrollHeight - 5) {
                if (this.noMore || this.loading) {
                    return;
                }
                this.loading = true;
                this.noMore = await this.toastListPaginateAction();
                console.log(this.noMore);
                this.loading = false;
            }
        });
    },
};
</script>
