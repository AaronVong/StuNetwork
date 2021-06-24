<template>
    <div class="w-full mb-3" v-if="this.visible">
        <form method="POST" @submit.prevent="this.handleCommentSubmit">
            <div>
                <textarea
                    v-model="this.content"
                    class="
                        focus:outline-none
                        resize-none
                        w-full
                        bg-transparent
                        text-lg text-black
                        p-3
                    "
                    placeholder="Viết bình luận..."
                    rows="3"
                    name="content"
                ></textarea>
            </div>
            <span
                class="inline-block text-red-500 mb-3"
                v-if="this.commentValidates.comment"
            >
                {{ this.commentValidates.comment.shift() }}
            </span>
            <div class="ml-auto">
                <button
                    type="submit"
                    :class="` btn-basic
                        rounded-full
                        py-2
                        px-3
                        text-white
                        font-medium
                        focus:ring-2 focus:ring-blue-500
                        focus:outline-none
                        ${this.content.length == 0 ? 'btn-isDisabled' : ''}`"
                    :disabled="this.content.length == 0 ? true : false"
                >
                    Bình luận
                </button>
            </div>
        </form>
    </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
export default {
    name: "CommentForm",
    data() {
        return {
            content: "",
        };
    },
    methods: {
        ...mapActions(["commentAction", "replyAction"]),
        toggleCommentDialog() {
            this.visible = !this.visible;
        },
        async handleCommentSubmit() {
            const loading = this.$loading({
                fullscreen: true,
                text: "Đang xử lý, vui lòng chờ...",
            });
            let ok = false;
            if (this.isComment) {
                ok = await this.commentAction({
                    toast_id: this.toast_id,
                    content: this.content,
                });
            } else {
                ok = await this.replyAction({
                    content: this.content,
                    comment_id: this.parentId,
                });
            }
            if (ok != false) {
                this.content = "";
                this.$emit("closeCommentForm");
            } else {
                if (this.commentErrorMessage) {
                    this.$message.error({
                        message: this.commentErrorMessage,
                    });
                }
            }
            loading.close();
        },
    },
    computed: {
        ...mapGetters([
            "commentValidates",
            "commentErrorMessage",
            "commentInfoMessage",
        ]),
    },
    props: {
        toast_id: {
            type: Number,
            default: null,
        },
        isComment: {
            // comment : true, reply : false
            type: Boolean,
            default: false,
        },
        visible: {
            type: Boolean,
            default: false,
        },
        parentId: { type: Number, default: -1 },
    },
    emits: ["closeCommentForm"],
};
</script>
