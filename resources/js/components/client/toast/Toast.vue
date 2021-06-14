<template>
    <div class="toast p-3 hover:bg-gray-50 transition-colors">
        <div class="toast__head w-full flex mb-3">
            <div class="flex-grow-0 flex-shrink-0 max-w-xs mr-2">
                <img
                    v-if="toast.user.profile.avatarUrl"
                    :src="toast.user.profile.avatarUrl"
                    class="block md:w-16 md:h-16 rounded-full h-12 w-12"
                />
                <img
                    v-else
                    src="https://via.placeholder.com/50"
                    class="block md:w-16 md:h-16 rounded-full h-12 w-12"
                />
            </div>
            <div class="flex flex-col">
                <a
                    v-bind:href="`/profile/${toast.user.username}`"
                    class="hover:underline"
                >
                    <span class="font-medium">{{
                        toast.user.profile.fullname
                    }}</span>
                </a>
                <span class="font-normal text-gray-500 emphasis"
                    ><i>{{ toast.user.username }}</i></span
                >
            </div>
            <div>
                <span class="px-2 font-normal text-gray-500">{{
                    this.diffForHumans
                }}</span>
            </div>
            <!-- Tools -->
            <ToastTools :owned="this.owned" :toastID="toast.id" />
        </div>
        <div class="toast__body w-full mb-3 px-3 min-h-full">
            <div class="mb-3">
                <!-- Toast contents place here -->
                <a :href="`/toast/${toast.id}`" class="block w-full">
                    <p>
                        {{ toast.content }}
                    </p>
                </a>
            </div>
            <!-- Toast files place here -->
            <div
                :class="`w-full grid gird-row-auto grid-cols-${
                    toast.files.length > 2
                        ? Math.round(toast.files.length / 2)
                        : toast.files.length
                } gap-2 items-center min-h-full`"
            >
                <ToastFile
                    v-for="(item, index) in toast.files"
                    :key="index"
                    :file="item"
                />
            </div>
        </div>
        <div class="toast__footer w-full flex">
            <div class="flex pill justify-evenly w-full">
                <!-- Like button here -->
                <div>
                    <button
                        v-if="!this.liked"
                        @click="this.handleLike"
                        type="button"
                        class="
                            pill-hover pill-hover--cycle pill-hover--red
                            focus:outline-none
                            text-pink-600
                        "
                    >
                        <i class="far fa-heart"></i>
                    </button>
                    <button
                        @click="this.handleLike"
                        type="button"
                        v-if="this.liked"
                        class="
                            pill-hover pill-hover--cycle pill-hover--red
                            focus:outline-none
                            text-pink-600
                        "
                    >
                        <i class="fas fa-heart"></i>
                    </button>
                    {{ this.toast.likes.length }}
                </div>
                <div class="mr-3 flex mr-6 pill">
                    <!-- Comment button place here -->
                    <a
                        class="
                            modal__btn
                            focus:outline-none
                            text-indigo-600
                            pill-hover pill-hover--cycle
                        "
                        href="#"
                    >
                        <button>
                            <i class="fas fa-comment" type="button"></i>
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import ToastFile from "./ToastFile.vue";
import ToastTools from "./ToastTools.vue";
import { format, register } from "timeago.js";
import { mapActions } from "vuex";
export default {
    name: "Toast",
    data() {
        return {
            diffForHumans: "",
        };
    },
    methods: {
        ...mapActions(["likeToastAction"]),
        async handleLike(e) {
            e.preventDefault();
            await this.likeToastAction(this.toast.id);
        },
    },
    props: {
        toast: Object,
        owned: Boolean,
        liked: Boolean,
    },
    components: { ToastFile, ToastTools },
    mounted() {
        const date = new Date(this.toast.created_at);
        const localeFnc = function (number, index, totalSec) {
            return [
                ["vừa xong", "một lúc"],
                ["%s giây trước", "trong %s giây"],
                ["1 phút trước", "trong 1 phút"],
                ["%s phút trước", "trong %s phút"],
                ["1 giờ trước", "trong 1 giờ"],
                ["%s giờ trước", "trong %s giờ"],
                ["1 ngày trước", "trong 1 ngày"],
                ["%s ngày trước", "trong %s ngày"],
                ["1 tuần trước", "trong 1 tuần"],
                ["%s tuần trước", "trong %s tuần"],
                ["1 tháng trước", "trong 1 tháng"],
                ["%s tháng trước", "trong %s tháng"],
                ["1 năm trước", "trong 1 năm"],
                ["%s năm trước", "trong %s năm"],
            ][index];
        };
        register("vi", localeFnc);
        this.diffForHumans = format(date, "vi");
    },
};
</script>
