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
                v-if="this.user.profile.avatarUrl"
                :src="this.user.profile.avatarUrl"
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
                {{ this.user.profile.fullname.split(" ").pop() }}
            </div>
        </div>
        <div class="flex flex-col gap-1 lg:flex lg:w-2/3 text-sm lg:text-base">
            <a
                :href="`/profile/${this.user.username}`"
                class="hover:underline truncate"
            >
                {{ this.user.profile.fullname }}
            </a>
            <i class="font-normal text-gray-500">
                {{ this.user.username }}
            </i>
        </div>
    </div>
</template>

<script>
import { mapGetters, mapMutations } from "vuex";
export default {
    name: "Stranger",
    data() {
        return {
            loading: false,
        };
    },
    props: {
        user: Object,
    },
    methods: {
        ...mapMutations(["changeChatWith"]),
        handleChatWith(e) {
            e.preventDefault();
            const profile = { ...this.user.profile };
            profile.user = { id: this.user.id, username: this.user.username };
            this.changeChatWith(profile);
            this.$emit("itemClicked");
        },
    },
    emits: ["itemClicked"],
};
</script>
