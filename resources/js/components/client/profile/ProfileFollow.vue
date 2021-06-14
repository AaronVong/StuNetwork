<template>
    <div class="flex justify-end items-center h-full">
        <button
            v-if="this.followable"
            @click="this.handleFollow"
            type="button"
            class="
                follow
                w-32
                h-12
                bg-white
                rounded-full
                border border-blue-400
                text-blue-400
                font-medium
                hover:bg-blue-100
                transition-colors
                duration-200
                focus:outline-none
            "
        >
            Theo dõi
        </button>
        <button
            v-else
            @click="this.handleFollow"
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
</template>

<script>
import { mapActions } from "vuex";
export default {
    name: "ProfileFollow",
    props: {
        followable: Boolean,
        profile_id: Number,
    },
    methods: {
        ...mapActions(["toggleFollow"]),
        async handleFollow(e) {
            e.preventDefault();
            await this.toggleFollow(this.profile_id);
        },
    },
};
</script>
