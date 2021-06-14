<template>
    <form
        method="post"
        enctype="multipart/form-data"
        id="toast-form"
        class="w-full"
        @submit="this.handleToastSubmit"
    >
        <div>
            <!-- Message Place Here-->
            <span class="text-red-500 font-bold" v-if="this.message != ''">
                {{ this.message }}
            </span>
            <textarea
                v-model="this.form.content"
                class="
                    focus:outline-none
                    resize-none
                    w-full
                    bg-transparent
                    text-lg text-black
                "
                placeholder="Chia sẽ những gì bạn nghĩ..."
                form="toast-form"
                rows="3"
                name="content"
            ></textarea>
        </div>
        <div class="flex items-center">
            <div class="flex items-center w-4/6 gap-3">
                <div class="pill-hover--cycle pill-hover text-blue-400">
                    <!-- Emoji Here-->
                    <button type="button" class="focus:outline-none">
                        <i class="far fa-smile-beam text-xl"></i>
                    </button>
                </div>
                <div class="w-full text-blue-400">
                    <!-- File Upload Here-->
                    <FileUpload
                        v-bind:fileList="this.form.fileList"
                        v-bind:types="this.extensions.join(',')"
                        v-on:selectFile="this.handleFileChange"
                        v-on:removeFile="this.handleFileRemove"
                    ></FileUpload>
                </div>
            </div>
            <div class="ml-auto">
                <button
                    type="submit"
                    id="toast-form__submit"
                    :class="` btn-basic
                        rounded-full
                        py-2
                        px-3
                        text-white
                        font-medium
                        focus:ring-2 focus:ring-blue-500
                        focus:outline-none
                        ${
                            this.form.content.length == 0
                                ? 'btn-isDisabled'
                                : ''
                        }`"
                    :disabled="this.form.content.length == 0 ? true : false"
                >
                    {{ this.btnText }}
                </button>
            </div>
        </div>
        <!-- Validates place here -->
        <div class="text-left">
            <span class="text-red-500" v-if="this.validates.content">
                {{ this.validates.content.shift() }}
            </span>
            <span class="text-red-500" v-if="this.validates.subfile">
                {{ this.validates.subfile.shift() }}
            </span>
            <span class="text-red-500" v-if="this.validates['files_upload']">
                {{ this.validates["files_upload"].shift() }}
            </span>
        </div>
    </form>
</template>

<script>
import FileUpload from "../../utilities/FileUpload";
import { mapActions, mapGetters } from "vuex";
export default {
    name: "ToastForm",
    data() {
        return {
            form: {
                content: "",
                fileList: [],
                deletedFiles: [],
            },
            extensions: [
                ".jpg",
                ".jpeg",
                ".png",
                ".gif",
                ".docx",
                ".doc",
                ".ppt",
                ".pptx",
                ".xls",
                ".xlsx",
                ".txt",
            ],
        };
    },
    props: {
        discard: {
            type: Boolean,
            default: false,
        },
        native_method: {
            type: String,
            default: "POST",
        },
        toastID: {
            type: Number,
            default: -1,
        },
        btnText: {
            type: String,
            default: "Đăng bài",
        },
    },
    computed: {
        ...mapGetters(["message", "validates"]),
    },
    components: { FileUpload },
    methods: {
        ...mapActions(["createToastAction", "getToastById", "editToastAction"]),
        isValidFile(file) {
            const filename = file.name;
            const fileExtension = filename.slice(
                ((filename.lastIndexOf(".") - 1) >>> 0) + 2
            );
            return this.extensions.some((item) =>
                item.indexOf(fileExtension) != -1 ? true : false
            );
        },
        async handleToastSubmit(e) {
            e.preventDefault();
            let formData = new FormData();
            let fileError = false;
            // Chuyển đổi danh sách file vào sang File và cho vào FormData
            this.form.fileList.forEach((file, index) => {
                if (this.isValidFile(file) == false) {
                    fileError = true;
                } else {
                    if (file.hasOwnProperty("raw")) {
                        formData.set(
                            `files_upload[${index}]`,
                            file.raw,
                            file.name
                        );
                    }
                }
            });
            formData.set("content", this.form.content);
            if (this.toastID !== -1) {
                formData.set(
                    "deletedFiles",
                    JSON.stringify(this.form.deletedFiles)
                );
            }
            if (fileError) {
                this.$message.warning("Chỉ hộ trợ file văn bản hoặc hình ảnh!");
            } else {
                const loading = this.$loading({
                    fullscreen: true,
                    text: "Đang xử lý, vui lòng chờ...",
                });
                // Edit toast case
                if (
                    this.native_method === "PUT" ||
                    this.native_method === "put"
                ) {
                    // Dặt put method cho laravel hiểu
                    formData.set("_method", "PUT");
                    const ok = await this.editToastAction({
                        formData,
                        id: this.toastID,
                    });
                    if (ok !== false) {
                        this.$emit("toastEdited");
                        this.$message.success({
                            message: "Toast đã được cập nhật!",
                            duration: 2000,
                        });
                        // cập nhật thông tin toast trên form
                        this.form.content = ok.content;
                        this.form.fileList = [...ok.files];
                    }
                } else {
                    // Create toast case
                    const ok = await this.createToastAction(formData);
                    if (ok) {
                        // reset local data
                        this.form.content = "";
                        this.form.fileList = [];
                        // close dialog when toast created
                        this.$emit("toastCreated");
                        // show some message
                        this.$message.success({
                            message: "Toast đã được thêm!",
                            duration: 2000,
                        });
                    }
                }
                loading.close();
            }
        },
        handleFileChange(file) {
            this.form.fileList.push(file);
        },
        handleFileRemove(file) {
            this.form.fileList = this.form.fileList.filter((item) => {
                if (item.name !== file.name) {
                    return true;
                }
                // Edit toast case
                if (this.toastID !== -1) {
                    this.form.deletedFiles.push(item.id);
                }
                return false;
            });
        },
    },
    updated() {
        // khi quick toast tắt
        if (this.discard) {
            this.form.content = "";
            this.form.fileList.splice(0, this.form.fileList.length);
        }
    },
    async mounted() {
        if (this.toastID !== -1) {
            const { content, files } = await this.getToastById(this.toastID);
            this.form.content = content;
            this.form.fileList = [...files];
        }
    },
    emits: ["toastEdited", "toastCreated"],
};
</script>
