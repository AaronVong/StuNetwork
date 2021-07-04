<template>
    <div class="w-full h-full divide-y grid grid-rows-2 border-b">
        <div class="w-full grid-span-1">
            <!-- Background Image Here -->
            <ProfileBackground :url="this.profile.backgroundUrl" />
        </div>
        <div class="grid-span-1 w-full px-3 py-2">
            <div class="relative flex items-center min-h-12 md:h-14">
                <div
                    class="
                        avatar
                        rounded-full
                        w-24
                        md:w-32 md:h-32 md:absolute md:left-6
                    "
                >
                    <img
                        v-if="this.profile.avatarUrl"
                        :src="this.profile.avatarUrl"
                        class="
                            block
                            md:w-full md:h-full
                            rounded-full
                            h-16
                            w-16
                            border border-gray-500
                        "
                    />
                    <div
                        v-else
                        class="
                            shirk-0
                            grow-0
                            rounded-full
                            flex
                            items-center
                            justify-center
                            bg-blue-700
                            text-white
                            w-24
                            h-24
                            md:w-32 md:h-32
                        "
                    >
                        {{ this.profile.fullname.split(" ").pop() }}
                    </div>
                </div>
                <!-- Edit profile button place here-->
                <EditProfileForm
                    v-if="!this.loading && this.owned"
                    :username="this.profile.user.username"
                ></EditProfileForm>
            </div>
            <div class="grid grid-cols-2">
                <ProfileInfo
                    :username="this.profile.user.username"
                    :rolename="this.rolename.join(',')"
                    :followingcount="this.followingcount"
                    :followedcount="this.followedcount"
                    :toastcount="this.toastcount"
                />
                <ProfileFollow
                    v-if="!this.owned"
                    :followable="this.followable"
                    :profile_id="this.profile.id"
                />
            </div>
        </div>
    </div>
    <ProfileNav
        :user_id="this.profile.user_id"
        :visitor="this.visitor"
        :owned="this.owned"
    />
</template>

<script>
import EditProfileForm from "./EditProfileForm.vue";
import ProfileBackground from "./ProfileBackground.vue";
import ProfileInfo from "./ProfileInfo.vue";
import ProfileFollow from "./ProfileFollow.vue";
import ProfileNav from "./ProfileNav.vue";
import { mapGetters, mapActions, mapMutations } from "vuex";
export default {
    name: "Profile",
    data() {
        return {
            rolename: [],
            loading: false,
        };
    },
    props: {
        owned: {
            type: Boolean,
            default: false,
        },
        roles: {
            type: Array,
            default: [],
        },
        user_profile: Object,
        followable: {
            type: Boolean,
            default: false,
        },
        visitor: Number,
        followingcount: Number,
        followedcount: Number,
        toastcount: Number,
    },
    components: {
        EditProfileForm,
        ProfileBackground,
        ProfileInfo,
        ProfileFollow,
        ProfileNav,
    },
    methods: {
        ...mapActions(["getProfileAction", "isFollowed"]),
        ...mapMutations(["setProfile"]),
    },
    computed: {
        ...mapGetters(["profile"]),
    },
    async beforeMount() {
        this.loading = true;
        this.setProfile(this.user_profile);
        this.roles.forEach((role) => {
            switch (role.name) {
                case "student":
                    this.rolename.push("sinh viên");
                    break;
                case "lecturer":
                    this.rolename.push("Giảng viên");
                    break;
                default:
                    break;
            }
        });
        // xem profile của người khác
        if (!this.owned) {
            await this.isFollowed({ profile_id: this.profile.id });
        }
    },
    mounted() {
        this.loading = false;
    },
};
</script>
