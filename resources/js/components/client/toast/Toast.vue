<template>
    <div class="toast p-3 hover:bg-gray-50 transition-colors list-item z-10">
        <div class="toast__head w-full flex mb-3">
            <div class="flex-grow-0 flex-shrink-0 max-w-xs mr-2">
                <img
                    v-if="toast.owner_profile.avatarUrl"
                    :src="toast.owner_profile.avatarUrl"
                    class="
                        block
                        md:w-16 md:h-16
                        rounded-full
                        h-12
                        w-12
                        border border-gray-500
                    "
                />
                <img
                    v-else
                    src="https://via.placeholder.com/50"
                    class="
                        block
                        md:w-16 md:h-16
                        rounded-full
                        h-12
                        w-12
                        border border-gray-500
                    "
                />
            </div>
            <div class="flex flex-col">
                <a
                    v-bind:href="`/profile/${toast.user.username}`"
                    class="hover:underline"
                >
                    <span class="font-medium">{{
                        toast.owner_profile.fullname
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
            <ToastTools
                :owned="this.owned"
                :toastID="this.toast.id"
                :followed="this.followed"
                :profileID="this.toast.owner_profile.id"
            />
        </div>
        <div class="toast__body w-full mb-3 px-3 min-h-full">
            <div class="mb-3" v-html="this.linkDetector(toast.content)"></div>
            <div
                :class="`w-full grid gird-row-auto grid-cols-1 md:grid-cols-${
                    toast.files.length > 2
                        ? Math.round(toast.files.length / 2)
                        : toast.files.length
                } gap-2 items-center min-h-full`"
            >
                <!-- Toast files place here -->
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
                <div class="flex items-center">
                    <button
                        type="button"
                        @click="this.handleLike"
                        :class="`HeartAnimation transition-all pill-hover pill-hover--cycle pill-hover--red
                            focus:outline-none
                            ${this.liked ? 'heart-animated' : ''}`"
                    ></button>
                    <sup v-show="this.toast.likesCount > 0">
                        {{ this.toast.likesCount }}
                    </sup>
                </div>
                <div class="flex pill items-center">
                    <!-- Comment Form trigger place here -->
                    <button
                        class="
                            modal__btn
                            focus:outline-none
                            text-blue-500
                            pill-hover pill-hover--cycle
                        "
                        style="width: 50px; height: 50px"
                        @click="
                            () => {
                                this.showComment = !this.showComment;
                            }
                        "
                        type="button"
                    >
                        <i class="fas fa-comment"></i>
                    </button>
                    <sup v-show="this.toast.commentCount > 0">
                        {{ this.toast.commentsCount }}
                    </sup>
                </div>
            </div>
        </div>
        <div>
            <CommentForm
                :toast_id="this.toast.id"
                :visible="this.showComment"
                :isComment="true"
            />
        </div>
    </div>
</template>

<script>
import ToastFile from "./ToastFile.vue";
import ToastTools from "./ToastTools.vue";
import CommentForm from "../comment/CommentForm.vue";
import { format, register } from "timeago.js";
import { mapActions, mapGetters } from "vuex";
import anchorme from "anchorme";
export default {
    name: "Toast",
    data() {
        return {
            diffForHumans: "",
            showComment: false,
        };
    },
    methods: {
        ...mapActions(["likeToastAction"]),
        async handleLike(e) {
            e.preventDefault();
            const $target = $(e.target);
            $target.prop("disabled", true);
            if ($target.hasClass("heart-animated")) {
                $target.removeClass("heart-animated");
            } else {
                $target.toggleClass("heart-animate");
            }
            const ok = await this.likeToastAction(this.toast.id);
            if (ok == null) {
                this.$message({
                    type: "error",
                    message: this.toastErrorMessage,
                });
            }
            setTimeout(() => {
                $target.prop("disabled", false);
            }, 800);
        },
        linkDetector(text) {
            try {
                const result = anchorme({
                    input: text,
                    options: {
                        attributes: {
                            target: "_blank",
                            class: "text-blue-500 hover:text-blue-600 hover:underline",
                        },
                    },
                });
                return result;
            } catch (error) {
                return text;
            }
        },
    },
    props: {
        toast: Object,
        owned: Boolean,
        liked: Boolean,
        followed: Boolean,
    },
    computed: {
        ...mapGetters(["toastErrorMessage"]),
    },
    components: { ToastFile, ToastTools, CommentForm },
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
