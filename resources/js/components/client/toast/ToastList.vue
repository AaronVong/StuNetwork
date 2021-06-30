<template>
    <div class="border-b">
        <ul class="divide-y infinite-list-wrapper" style="overflow: auto">
            <Toast
                v-for="(toast, index) in this.toastList"
                :key="index"
                :toast="toast"
                :owned="
                    this.owner === toast.user.id && this.owner === this.guest
                "
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
            "getToastListUploadedByUserId",
            "getToastListLikedByUserId",
        ]),
        ...mapMutations(["setToastList"]),
        async loadMoreToast() {},
    },
    props: {
        // user hiện hành (user đang đăng nhập)
        owner: Number,
        // user xem danh sách
        guest: Number,
        toast_list: { type: Array, default: null },
        // Loại list sẽ được sử dụng
        // list : mặc định,
        // uploaded: toast đã được upload bởi user,
        // liked: toast được like bởi user
        defaultAction: {
            type: String,
            default: "list",
        },
    },
    computed: {
        ...mapGetters(["toastList", "followings"]),
    },
    watch: {
        defaultAction: {
            handler: function (newval, oldVal) {
                console.log("updated");
                // this.noMore = false;
            },
            immediate: true,
        },
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
                if (this.defaultAction == "uploaded") {
                    this.noMore = await this.getToastListUploadedByUserId(
                        this.owner
                    );
                } else if (this.defaultAction == "liked") {
                    this.noMore = await this.getToastListLikedByUserId(
                        this.owner
                    );
                } else {
                    this.noMore = await this.toastListPaginateAction();
                }
                // console.log(this.noMore);
                this.loading = false;
            }
        });
    },
};
</script>
