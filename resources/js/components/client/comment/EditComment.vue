<template>
    <el-dialog
        v-model="this.localVisible"
        @close="
            () => {
                if (this.visible) this.localToggleEditComment();
            }
        "
    >
        <div>
            <h3 class="text-black text-lg md:text-xl mb-2">
                Chỉnh sửa bình luận
            </h3>
            <form @submit="this.handleSubmit">
                <div>
                    <div
                        v-if="this.commentValidates.comment"
                        class="text-red-500 text-lg mb-2"
                    >
                        {{ this.commentValidates.comment.shift() }}
                    </div>
                    <textarea
                        v-model="this.content"
                        class="
                            focus:outline-none
                            resize-none
                            w-full
                            bg-transparent
                            text-lg text-black
                            p-3
                            border
                        "
                        placeholder="Viết bình luận..."
                        rows="3"
                        name="content"
                    ></textarea>
                </div>
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
                        Sửa bình luận
                    </button>
                </div>
            </form>
        </div>
        <template #footer>
            <el-button @click="this.localToggleEditComment">Hủy bỏ</el-button>
        </template>
    </el-dialog>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
export default {
    name: "EditComment",
    data() {
        return {
            content: "",
            localVisible: false,
        };
    },
    props: {
        comment: Object,
        visible: Boolean,
    },
    watch: {
        visible: function (newVal, oldVal) {
            this.localVisible = newVal;
        },
    },
    computed: {
        ...mapGetters([
            "commentValidates",
            "commentErrorMessage",
            "commentInfoMessage",
        ]),
    },
    methods: {
        ...mapActions(["updateCommentAction"]),
        localToggleEditComment() {
            this.localVisible = !this.localVisible;
            // phải làm vậy vì el-dialog tự đồng sửa v-model,
            // và model đang dùng lại là props (read only) => lỗi
            // dialog không thể dùng trong popover nên phải dùng cách này
            this.$emit("toggleEditComment");
        },
        async handleSubmit(e) {
            e.preventDefault();
            const loading = this.$loading({
                fullscreen: true,
                text: "Đang xử lý, vui lòng chờ...",
            });
            const ok = await this.updateCommentAction({
                content: this.content,
                comment_id: this.comment.id,
            });
            if (ok == 200) {
                this.localToggleEditComment();
                this.content = "";
            }
            loading.close();
        },
    },
    emits: ["toggleEditComment"],
    mounted() {
        this.localVisible = this.visible;
        this.content = this.comment.comment;
    },
};
</script>
