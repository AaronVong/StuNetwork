<template>
    <el-popover placement="bottom" v-model:visible="this.toolsBoxVisible">
        <div class="flex flex-col justify-center items-center gap-3">
            <button
                v-if="this.processable"
                type="button"
                class="
                    text-center
                    focus:outline-none
                    hover:text-red-500
                    cursor-pointer
                "
                @click="this.confirmDeleteComment"
            >
                <i class="far fa-trash-alt mr-2"></i> Xóa bình luận
            </button>
            <button
                v-if="this.processable"
                class="
                    text-center
                    focus:outline-none
                    hover:text-yellow-500
                    cursor-pointer
                "
                @click="this.toggleEditComment"
            >
                <i class="far fa-edit mr-2"></i> Sửa bình luận
            </button>
        </div>
        <template #reference>
            <el-button class="cursor-pointer focus:outline-none">
                <i class="fas fa-ellipsis-h"></i>
            </el-button>
        </template>
    </el-popover>
    <EditComment
        :comment="this.comment"
        :visible="this.editCommentVisible"
        v-on:toggleEditComment="this.toggleEditComment"
    />
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import EditComment from "./EditComment.vue";
export default {
    name: "CommentTools",
    data() {
        return {
            toolsBoxVisible: false,
            editCommentVisible: false,
        };
    },
    props: {
        comment: Object,
        processable: {
            type: Boolean,
            default: false,
        },
    },
    computed: {
        ...mapGetters(["commentErrorMessage", "commentInfoMessage"]),
    },
    components: {
        EditComment,
    },
    methods: {
        ...mapActions(["deleteCommentAction"]),
        toggleEditComment() {
            this.editCommentVisible = !this.editCommentVisible;
        },
        async confirmDeleteComment() {
            try {
                const confirmed = await this.$confirm(
                    "Comment này sau khi xóa sẽ không khôi phục, bạn vẫn muốn xóa?",
                    "Cảnh báo",
                    {
                        confirmButtonText: "Đồng ý",
                        cancelButtonText: "Hủy bỏ",
                        type: "warning",
                    }
                );
                const ok = await this.deleteCommentAction(this.comment.id);
                if (ok) {
                    this.$message({
                        message: this.commentInfoMessage,
                        type: "success",
                    });
                    console.trace(this.commentInfoMessage);
                } else {
                    this.$message({
                        message: this.commentErrorMessage,
                        type: "error",
                    });
                }
            } catch (error) {
                console.log(error);
            }
        },
    },
};
</script>
