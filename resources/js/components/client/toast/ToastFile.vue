<template>
    <el-skeleton
        style="width: 240px"
        :loading="this.isLoading"
        animated
        v-if="file.type == 'image'"
    >
        <template #template>
            <el-skeleton-item
                variant="image"
                style="width: 100%; min-height: 200px; height: 250px"
            />
            <img
                :src="file.url"
                alt="template"
                class="hidden"
                v-on:load="this.handleLoad"
            />
        </template>
        <template #default>
            <div class="w-full flex justify-center items-center">
                <img
                    :src="file.url"
                    :alt="file.name"
                    class="rounded-3xl max-w-full max-h-full"
                />
            </div>
        </template>
    </el-skeleton>
    <div
        v-else
        class="w-full flex justify-center items-center max-w-full h-60 max-h-64"
    >
        <iframe
            class="w-full h-full"
            v-bind:src="`https://drive.google.com/file/d/${file.id}/preview`"
        ></iframe>
    </div>
</template>

<script>
export default {
    name: "ToastFile",
    data() {
        return {
            isLoading: true,
            filePreview: {},
        };
    },
    props: {
        file: Object,
    },
    methods: {
        handleLoad(event) {
            this.isLoading = false;
            event.target.remove();
        },
    },
};
</script>
