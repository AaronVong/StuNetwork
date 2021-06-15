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
                        md:w-32
                        md:h-32
                        md:absolute
                        md:left-6
                    "
                >
                    <img
                        v-if="this.profile.avatarUrl"
                        :src="this.profile.avatarUrl"
                        class="block md:w-full md:h-full rounded-full h-16 w-16"
                    />
                    <img
                        v-else
                        src="http://via.placeholder.com/50"
                        class="w-full rounded-full"
                    />
                </div>
                <!-- Edit profile button place here-->
                <EditProfileForm
                    v-if="!this.loading && this.owned"
                    :username="this.username"
                ></EditProfileForm>
            </div>
            <div class="grid grid-cols-2">
                <ProfileInfo
                    :username="this.username"
                    :rolename="this.rolename.join(',')"
                />
                <ProfileFollow
                    v-if="!this.owned"
                    :followable="this.followable"
                    :profile_id="this.profile.id"
                />
            </div>
        </div>
    </div>
    <ProfileNav :user_id="this.profile.user_id" :visitor="this.visitor" />
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
            type: Number,
            default: 0,
        },
        username: {
            type: String,
            default: "",
        },
        roles: {
            type: Array,
            default: [],
        },
        user_profile: Object,
        followable: {
            type: Number,
            default: 0,
        },
        visitor: Number,
    },
    components: {
        EditProfileForm,
        ProfileBackground,
        ProfileInfo,
        ProfileFollow,
        ProfileNav,
    },
    methods: {
        ...mapActions(["getProfileAction"]),
        ...mapMutations(["profileMutate", "setFollowState"]),
    },
    computed: {
        ...mapGetters(["profile"]),
    },
    async beforeMount() {
        this.loading = true;
        this.profileMutate(this.user_profile);
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
        if (!this.owned) {
            try {
                const response = await axios.post("/profile/followed", {
                    following_id: this.profile.id,
                });
                this.setFollowState(response.data.followed); // true, false
            } catch (error) {
                console.log(error);
            }
        }
    },
    mounted() {
        this.loading = false;
    },
};
</script>
