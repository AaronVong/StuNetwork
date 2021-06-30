<template>
    <el-tabs v-model="activeTab" @tab-click="this.handleTabClick">
        <img
            v-if="this.loading"
            src="/images/Pulse-1s-200px.svg"
            class="w-full h-16 bg-transparent"
        />
        <el-tab-pane label="Đã đăng" name="toasted">
            <ToastList
                v-if="this.activeTab == 'toasted'"
                :owner="this.user_id"
                :guest="this.visitor"
                :defaultAction="'uploaded'"
            />
        </el-tab-pane>
        <el-tab-pane label="Đã thích" name="liked">
            <ToastList
                v-if="this.activeTab == 'liked'"
                :owner="this.user_id"
                :guest="this.visitor"
                :defaultAction="'liked'"
            />
        </el-tab-pane>
        <el-tab-pane label="Đã theo dõi" name="followings">
            <ProfilePreview
                :owned="this.owned"
                v-if="this.activeTab == 'followings'"
            />
        </el-tab-pane>
    </el-tabs>
</template>

<script>
import { mapActions, mapGetters, mapMutations } from "vuex";
import ToastList from "../toast/ToastList.vue";
import ProfilePreview from "./ProfilePreview.vue";
export default {
    name: "ProfileNav",
    data() {
        return {
            activeTab: "toasted",
            loading: false,
        };
    },
    components: {
        ToastList,
        ProfilePreview,
    },
    props: {
        user_id: Number, // profile owner
        visitor: Number, // guest
        owned: Boolean,
    },
    computed: {
        ...mapGetters(["toastList", "followings"]),
    },
    methods: {
        ...mapActions([
            "getToastListUploadedByUserId",
            "getToastListLikedByUserId",
            "getToastListFollowedByUserId",
            "getProfilesFollowedByUserId",
        ]),
        ...mapMutations(["setToastList", "setPage"]),
        async handleTabClick(tab, event) {
            this.clickTab = true;
            this.setToastList([]);
            this.loading = true;
            switch (tab.props.name) {
                case "toasted":
                    this.setPage(1);
                    await this.getToastListUploadedByUserId(this.user_id);
                    break;
                case "liked":
                    this.setPage(1);
                    await this.getToastListLikedByUserId(this.user_id);
                    break;
                case "followings":
                    await this.getProfilesFollowedByUserId(this.user_id);
                    break;
                default:
                    break;
            }
            this.loading = false;
            this.clickTab = false;
        },
    },
    async mounted() {
        await this.getToastListUploadedByUserId(this.user_id);
    },
};
</script>
