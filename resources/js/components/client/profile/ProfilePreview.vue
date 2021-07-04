<template>
    <div
        v-for="(profile, index) in this.followings"
        :key="index"
        class="w-full px-3 border-b"
    >
        <div class="w-full flex mb-3">
            <div class="flex-grow-0 flex-shrink-0 max-w-xs mr-2">
                <img
                    v-if="profile.avatarUrl"
                    :src="profile.avatarUrl"
                    class="block md:w-16 md:h-16 rounded-full h-12 w-12"
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
                        md:w-16 md:h-16
                        h-12
                        w-12
                    "
                >
                    {{ profile.fullname.split(" ").pop() }}
                </div>
            </div>
            <div class="flex flex-col">
                <a
                    v-bind:href="`/profile/${profile.username}`"
                    class="hover:underline"
                >
                    <span class="font-medium">{{ profile.fullname }}</span>
                </a>
                <span class="font-normal text-gray-500"
                    ><i>{{ profile.username }}</i></span
                >
            </div>
            <!-- Tools -->
            <div
                class="ml-auto flex justify-end items-center h-full"
                v-if="this.owned"
            >
                <button
                    @click.prevent="this.handleUnfollow(profile.id)"
                    v-loading="this.loading"
                    type="button"
                    class="
                        unfollow
                        w-32
                        h-12
                        bg-blue-500
                        rounded-full
                        text-white
                        font-medium
                        hover:bg-red-700
                        transition-colors
                        duration-200
                        focus:outline-none
                    "
                ></button>
            </div>
        </div>
    </div>
</template>

<script>
import { mapActions, mapGetters, mapMutations } from "vuex";
import ProfileFollow from "./ProfileFollow.vue";
export default {
    name: "ProfilePreview",
    data() {
        return {
            loading: false,
        };
    },
    props: {
        owned: Boolean,
    },
    computed: {
        ...mapGetters(["followings"]),
    },
    components: { ProfileFollow },
    methods: {
        ...mapActions(["toggleFollow"]),
        ...mapMutations(["setFollowingsList"]),
        async handleUnfollow(id) {
            this.loading = true;
            await this.toggleFollow(id);
            // const newFollowings = this.followings.filter(
            //     (profile) => profile.id != id
            // );
            // this.setFollowingsList(newFollowings);
            this.loading = false;
        },
    },
};
</script>
