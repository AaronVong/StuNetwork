<template>
    <div
        id="chat-list-drag"
        v-show="!this.visible"
        class="
            absolute
            bg-blue-400
            hover:bg-blue-600
            bottom-1/4
            left-24
            w-16
            h-16
            text-center
            rounded-full
            z-20
            cursor-move
            flex
            items-center
            justify-center
            lg:hidden
        "
    >
        <button
            type="button"
            @click="openChatList"
            class="text-white cursor-pointer"
        >
            <i class="far fa-comment-dots text-medium"></i>
        </button>
    </div>
    <div
        :class="`
            transition-width
            col-span-1
            absolute
            right-0
            right-0
            top-0
            w-0
            h-full
            overflow-x-hidden
            bg-gray-200
            lg:bg-white
            ${this.visible ? 'w-2/5' : ''}
            flex flex-col
            border-r
            border-l
            lg:border-l-0
            lg:relative lg:w-full lg:h-full
            `"
    >
        <div
            v-if="this.followings.length > 0"
            class="w-full h-full flex flex-col"
        >
            <ChatListItem
                v-for="(item, index) in this.followings"
                :key="index"
                :profile="item"
            />
        </div>
        <div
            v-else
            class="
                w-full
                h-full
                flex flex-col
                justify-center
                items-center
                text-wrap text-lg
            "
        >
            <h4 class="text-lg font-md p-4">Theo dõi để có thể nhắn tin...</h4>
        </div>
        <button
            type="button"
            @click="this.visible = false"
            class="
                w-full
                h-6
                bg-gray-300
                text-red-500
                hover:bg-red-500 hover:text-white
                py-4
                flex
                justify-center
                items-center
                lg:hidden
            "
        >
            <i class="fas fa-times fa-2x"></i>
        </button>
    </div>
</template>

<script>
import ChatListItem from "./ChatListItem.vue";
import { mapMutations, mapGetters } from "vuex";
export default {
    name: "ChatList",
    data() {
        return {
            visible: false,
        };
    },
    props: {
        chat_list: {
            type: Array,
            default: [],
        },
    },
    computed: {
        ...mapGetters(["followings"]),
    },
    components: { ChatListItem },
    methods: {
        ...mapMutations(["setFollowingsList"]),
        openChatList() {
            this.visible = true;
        },
    },
    mounted() {
        this.setFollowingsList(this.chat_list);
    },
};
</script>
