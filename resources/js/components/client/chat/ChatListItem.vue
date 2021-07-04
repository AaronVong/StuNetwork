<template>
    <div
        @click="handleChatWith"
        class="
            w-full
            flex
            items-center
            gap-3
            hover:bg-gray-100
            p-3
            cursor-pointer
            border-b
            lg:border-gray-100
            border-white
        "
    >
        <div class="shirk-0 grow-0 w-16 rounded-full border">
            <img
                v-if="this.profile.avatarUrl"
                :src="this.profile.avatarUrl"
                class="block w-full h-16 rounded-full"
            />
            <div
                v-else
                class="
                    shirk-0
                    grow-0
                    w-16
                    h-16
                    rounded-full
                    flex
                    items-center
                    justify-center
                    bg-blue-700
                    text-white
                "
            >
                {{ this.profile.fullname.split(" ").pop() }}
            </div>
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
                class="hover:underline truncate"
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
import { mapActions, mapMutations } from "vuex";
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
        ...mapMutations(["changeChatWith"]),
        ...mapActions(["toggleFollow"]),
        async handleUnfollow(e) {
            e.preventDefault();
            this.loading = true;
            await this.toggleFollow(this.profile.id);
            this.loading = false;
        },
        handleChatWith(e) {
            e.preventDefault();
            this.changeChatWith(this.profile);
            this.$emit("itemClicked");
        },
    },
    emits: ["itemClicked"],
};
</script>
