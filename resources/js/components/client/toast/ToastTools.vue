<template>
    <div class="ml-auto">
        <el-popover placement="bottom" v-model:visible="this.toolsBoxVisible">
            <div class="flex flex-col gap-3 justify-center">
                <a
                    :href="`/toast/${toastID}`"
                    class="text-center hover:text-blue-500 cursor-pointer"
                >
                    <i class="far fa-eye mr-2"></i>Xem bài
                </a>
                <button
                    v-if="this.owned"
                    type="button"
                    class="
                        text-center
                        focus:outline-none
                        hover:text-red-500
                        cursor-pointer
                    "
                    @click="this.confirmDeleteToast"
                >
                    <i class="far fa-trash-alt mr-2"></i> Xóa bài
                </button>
                <button
                    class="
                        text-center
                        focus:outline-none
                        hover:text-yellow-500
                        cursor-pointer
                    "
                    v-if="this.owned"
                    @click="this.toggleEditToast"
                >
                    <i class="far fa-edit mr-2"></i> Sửa bài
                </button>
                <div
                    v-if="!this.owned"
                    class="flex justify-center items-center"
                >
                    <button
                        v-if="!this.followable"
                        @click.prevent="this.handleFollow"
                        type="button"
                        class="
                            text-center
                            focus:outline-none
                            hover:text-blue-500
                            cursor-pointer
                        "
                    >
                        Theo dõi
                    </button>
                    <button
                        v-else
                        @click.prevent="this.handleFollow"
                        type="button"
                        class="
                            text-center
                            focus:outline-none
                            hover:text-red-500
                            cursor-pointer
                        "
                    >
                        Hủy theo dõi
                    </button>
                </div>
            </div>
            <template #reference>
                <el-button class="cursor-pointer focus:outline-none">
                    <i class="fas fa-ellipsis-h"></i>
                </el-button>
            </template>
        </el-popover>
    </div>
    <!-- Dialog hiện form cạp nhật toast-->
    <el-dialog
        title="Cập nhật Toast"
        width="80%"
        v-model="this.editToastVisible"
        @closed="
            () => {
                if (this.editToastVisible) this.editToastVisible = false;
            }
        "
    >
        <div class="w-full">
            <ToastForm
                :native_method="'PUT'"
                :toastID="this.toastID"
                :btnText="'Cập nhật'"
                v-on:toastEdited="this.toggleEditToast"
            ></ToastForm>
        </div>
        <template #footer>
            <div class="flex justify-end items-center gap-4">
                <el-button @click="this.toggleEditToast">Hủy bỏ</el-button>
            </div>
        </template>
    </el-dialog>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import ToastForm from "./ToastForm.vue";
export default {
    name: "ToastTools",
    data() {
        return {
            toolsBoxVisible: false,
            editToastVisible: false,
            followable: false,
        };
    },
    props: {
        owned: Boolean,
        toastID: {
            type: Number,
            default: -1,
        },
        followed: Boolean,
        profileID: Number,
    },
    computed: {
        ...mapGetters(["toastInfoMessage", "toastErrorMessage", "followings"]),
    },
    methods: {
        ...mapActions(["deleteToastAction", "toggleFollow"]),
        toggleToolsBox() {
            this.toolsBoxVisible = !this.toolsBoxVisible;
        },
        toggleEditToast() {
            this.editToastVisible = !this.editToastVisible;
        },
        async confirmDeleteToast() {
            this.$confirm(
                "Dữ liệu liên quan đến toast này sẽ bị xóa và không thể phục hồi, bạn vẫn muốn xóa?",
                "Cảnh báo",
                {
                    confirmButtonText: "Đồng ý",
                    cancelButtonText: "Hủy bỏ",
                    type: "warning",
                }
            ).then(async () => {
                const loading = this.$loading({
                    fullscreen: true,
                    text: "Đang xử lý, vui lòng chờ...",
                });
                const ok = await this.deleteToastAction(this.toastID);
                if (ok) {
                    this.$message({
                        type: "success",
                        message: this.toastInfoMessage,
                    });
                } else {
                    this.$message({
                        type: "error",
                        message: this.toastErrorMessage,
                    });
                }
                loading.close();
            });
        },
        async handleFollow() {
            await this.toggleFollow(this.profileID);
            this.followable = !this.followable;
        },
    },
    mounted() {
        this.followable = this.followed;
    },
    watch: {
        followed: function (newVal, oldVal) {
            this.followable = newVal;
        },
    },
    components: { ToastForm },
};
</script>
