<template>
    <el-tabs v-model="activeTab" @tab-click="this.handleTabClick">
        <el-tab-pane label="Đã đăng" name="1">
            <ToastList :owner="this.visitor" />
        </el-tab-pane>
        <el-tab-pane label="Đã thích" name="2">
            <ToastList :owner="this.visitor" />
        </el-tab-pane>
        <el-tab-pane label="Đã theo dõi" name="3">Đã theo dõi</el-tab-pane>
    </el-tabs>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import ToastList from "../toast/ToastList.vue";
export default {
    name: "ProfileNav",
    data() {
        return {
            activeTab: "1",
        };
    },
    components: {
        ToastList,
    },
    props: {
        user_id: Number,
        visitor: Number,
    },
    computed: {
        ...mapGetters(["toastList"]),
    },
    methods: {
        ...mapActions([
            "setToastList",
            "getToastListUploadedByUserId",
            "getToastListLikedByUserId",
            "getToastListFollowedByUserId",
        ]),
        async handleTabClick(tab, event) {
            switch (tab.props.name) {
                case "1":
                    await this.getToastListUploadedByUserId(this.user_id);
                    break;
                case "2":
                    await this.getToastListLikedByUserId(this.user_id);
                    break;
                case "3":
                    break;
                default:
                    break;
            }
        },
    },
    async mounted() {
        await this.getToastListUploadedByUserId(this.user_id);
    },
};
</script>
