<template>
    <el-tabs v-model="activeTab" @tab-click="this.handleTabClick">
        <el-tab-pane label="Đã đăng" name="toasted">
            <img
                v-if="this.loading"
                src="/images/Pulse-1s-200px.svg"
                class="w-full h-16 bg-transparent"
            />
            <ToastList
                v-if="this.activeTab == 'toasted'"
                :owner="this.user_id"
                :guest="this.visitor"
                :defaultAction="this.activeTab"
            />
        </el-tab-pane>
        <el-tab-pane label="Đã thích" name="liked">
            <img
                v-if="this.loading"
                src="/images/Pulse-1s-200px.svg"
                class="w-full h-16 bg-transparent"
            />
            <ToastList
                v-if="this.activeTab == 'liked'"
                :owner="this.user_id"
                :guest="this.visitor"
                :defaultAction="this.activeTab"
            />
        </el-tab-pane>
        <el-tab-pane label="Đã theo dõi" name="followings">
            <img
                v-if="this.loading"
                src="/images/Pulse-1s-200px.svg"
                class="w-full h-16 bg-transparent"
            />
            <ProfilePreview
                v-if="this.activeTab == 'followings'"
                :owned="this.owned"
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
            this.loading = true;
            this.setToastList(null);
            this.setPage(1);
            const name = tab.props.name;
            if (name == "toasted") {
                await this.getToastListUploadedByUserId(this.user_id);
                console.log("profile nav");
            } else if (name == "liked") {
                await this.getToastListLikedByUserId(this.user_id);
                console.log("profile nav");
            } else {
                await this.getProfilesFollowedByUserId(this.user_id);
                console.log("profile nav");
            }
            // switch (tab.props.name) {
            //     case "toasted":
            //         await this.getToastListUploadedByUserId(this.user_id);
            //         break;
            //     case "liked":
            //         await this.getToastListLikedByUserId(this.user_id);
            //         break;
            //     case "followings":
            //         await this.getProfilesFollowedByUserId(this.user_id);
            //         break;
            //     default:
            //         break;
            // }
            this.loading = false;
        },
    },
    async mounted() {
        if (this.activeTab == "toasted") {
            await this.getToastListUploadedByUserId(this.user_id);
        }
        console.log("mounted");
    },
};
</script>
