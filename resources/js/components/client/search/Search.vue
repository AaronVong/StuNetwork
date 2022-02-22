<template>
    <form @submit="this.handleSubmit" class="block w-full">
        <input
            type="text"
            v-model="this.username"
            class="
                px-3
                border
                rounded-lg
                h-12
                mb-3
                w-full
                text-md
                focus:outline-none focus:ring focus:ring-1 focus:ring-blue-400
            "
            placeholder="Nhập tên tài khoản..."
        />
    </form>
    <ul v-if="this.foundList" class="h-64 border overflow-y-auto list-none p-3">
        <li v-if="this.foundList.length <= 0">
            <h5 class="text-center py-3 text-lg">Không có kết quả.</h5>
        </li>
        <li
            v-else
            v-for="(user, index) in this.foundList"
            :key="index"
            @click="this.handleClick($event, index)"
            class="
                w-full
                flex
                items-center
                mb-3
                hover:bg-gray-100
                cursor-pointer
            "
        >
            <div class="flex-grow-0 flex-shrink-0 max-w-xs mr-2">
                <img
                    v-if="user.profile.avatarUrl"
                    :src="user.profile.avatarUrl"
                    class="
                        block
                        md:w-16 md:h-16
                        rounded-full
                        h-12
                        w-12
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
                        md:w-16 md:h-16
                        h-12
                        w-12
                    "
                >
                    {{ user.profile.fullname.split(" ").pop() }}
                </div>
            </div>
            <div class="flex flex-col">
                <a
                    v-bind:href="`/profile/${user.username}`"
                    class="hover:underline flex items-center"
                >
                    <span class="font-medium truncate w-24">{{
                        user.profile.fullname
                    }}</span>
                </a>
                <span class="font-normal text-gray-500 emphasis"
                    ><i>{{ user.username }}</i></span
                >
            </div>
            <div class="flex items-center ml-auto">
                <button
                    @click="this.follow($event, user.profile.id, index)"
                    v-if="!user.followed && user.id != this.user.id"
                    type="button"
                    class="
                        follow
                        py-2
                        px-4
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
                    @click="this.follow($event, user.profile.id, index)"
                    v-else-if="user.followed && user.id != this.user.id"
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
                <div v-else></div>
            </div>
        </li>
    </ul>
</template>

<script>
import axios from "axios";
import { mapActions, mapGetters, mapMutations } from "vuex";
export default {
    name: "Search",
    data() {
        return {
            username: "",
            foundList: null,
        };
    },
    props: {
        user: Object,
    },
    computed: {
        ...mapGetters(["followed"]),
    },
    methods: {
        ...mapActions(["toggleFollow"]),
        ...mapMutations(["changeChatWith"]),
        async handleSubmit(e) {
            e.preventDefault();
            try {
                const response = await axios.get(`/search/${this.username}`);
                this.foundList = [...response.data.users];
            } catch (error) {
                console.log(error);
            }
        },
        async follow(e, profileID, index) {
            e.preventDefault();
            await this.toggleFollow(profileID);
            this.foundList[index]["followed"] = this.followed;
        },
        handleClick(e, index) {
            if (!$(e.target).is("button") || !$(e.target).is("a")) {
                const regex = new RegExp("^(/chat)(/*)?$", "i");
                const result = regex.test(window.location.pathname);
                if (result) {
                    const profile = {
                        ...this.foundList[index].profile,
                        user: {
                            id: this.foundList[index].id,
                            username: this.foundList[index].username,
                        },
                    };
                    this.changeChatWith(profile);
                    this.$emit("close", e);
                }
            }
        },
    },
    mounted() {},
    emits: ["close"],
};
</script>
