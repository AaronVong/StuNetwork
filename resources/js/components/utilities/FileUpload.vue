<template>
    <el-upload
        name="files"
        action=""
        :file-list="fileList"
        :multiple="true"
        :list-type="'picture'"
        :on-exceed="this.handleExceed"
        :auto-upload="false"
        :on-change="this.handleChange"
        :on-remove="this.handleRemove"
        :limit="4"
        :accept="types"
    >
        <div>
            <button
                type="button"
                class="focus:outline-none pill-hover pill-hover--cycle"
            >
                <i class="fas fa-photo-video"></i>
            </button>
        </div>
    </el-upload>
</template>

<script>
export default {
    name: "FileUpload",
    props: {
        fileList: {
            type: Array,
        },
        types: {
            type: String,
        },
    },
    methods: {
        handleExceed(files, fileList) {
            this.$message.warning(
                `Tối đa 4 ảnh được phép upload, số ảnh bạn chọn là ${files.length}`
            );
        },
        handleChange(file, fileList) {
            this.$emit("selectFile", file);
        },
        handleRemove(file, fileList) {
            this.$emit("removeFile", file);
        },
    },
    emits: ["removeFile", "selectFile"],
};
</script>
