<template>
    <div
        @click="this.toggleSearchPanel"
        class="
            flex
            justify-evenly
            items-center
            w-full
            h-full
            pill-hover
            font-medium
            text-2xl text-gray-700
            cursor-pointer
        "
    >
        <i class="fas fa-search"></i>
        <span class="hidden lg:inline-block lg:text-base xl:text-xl lg:w-4/6"
            >Tìm kiếm</span
        >
    </div>
    <el-dialog
        title="Tìm kiếm"
        width="80%"
        v-model="searchPanelVisible"
        @closed="
            () => {
                if (this.searchPanelVisible) this.searchPanelVisible = false;
            }
        "
    >
        <div class="w-full">
            <Search :user="this.user" />
        </div>
        <template #footer>
            <div class="flex justify-end items-center gap-4">
                <el-button @click="this.toggleSearchPanel">Hủy bỏ</el-button>
            </div>
        </template>
    </el-dialog>
</template>

<script>
import Search from "./Search.vue";
export default {
    name: "NavSearch",
    data() {
        return {
            foundList: null,
            searchPanelVisible: false,
        };
    },
    props: {
        user: Object,
    },
    components: {
        Search,
    },
    methods: {
        toggleSearchPanel(e) {
            e.preventDefault();
            this.searchPanelVisible = !this.searchPanelVisible;
        },
    },
    mounted() {},
};
</script>
