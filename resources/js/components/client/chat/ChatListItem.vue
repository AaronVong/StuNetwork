<template>
    <div
        class="
            w-full
            flex
            items-center
            gap-3
            hover:bg-gray-100
            p-3
            cursor-pointer
            border-b
        "
    >
        <div class="shirk-0 grow-0 w-16 rounded-full">
            <img
                :src="this.profile.avatarUrl"
                class="block w-full rounded-full border"
            />
        </div>
        <div
            class="
                flex flex-col
                gap-1
                hidden
                lg:flex lg:w-1/3
                text-sm
                lg:text-base
            "
        >
            <a
                :href="`/profile/${this.profile.user.username}`"
                class="hover:underline"
            >
                {{ this.profile.fullname }}
            </a>
            <i class="font-normal text-gray-500">
                {{ this.profile.user.username }}
            </i>
        </div>
        <div
            v-loading="this.loading"
            class="lg:ml-auto h-full lg:w-1/3 w-1/2 flex items-center"
        >
            <button
                @click="this.handleUnfollow"
                type="button"
                class="
                    unfollow
                    w-full
                    h-9
                    bg-blue-500
                    rounded-full
                    text-white
                    font-light
                    hover:bg-red-700
                    transition-colors
                    duration-200
                    focus:outline-none
                "
            ></button>
        </div>
    </div>
</template>

<script>
import { mapActions } from "vuex";
export default {
    name: "ChatListItem",
    data() {
        return {
            loading: false,
        };
    },
    props: {
        profile: {
            type: Object,
            required: true,
        },
    },
    methods: {
        ...mapActions(["toggleFollow"]),
        async handleUnfollow(e) {
            e.preventDefault();
            this.loading = true;
            await this.toggleFollow(this.profile.id);
            this.loading = false;
        },
    },
};
</script>
