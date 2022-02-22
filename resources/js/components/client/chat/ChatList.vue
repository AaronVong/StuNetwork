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
            xl:hidden
        "
    >
        <button
            type="button"
            @click="this.toggleChatList"
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
            top-0
            w-0
            h-full
            overflow-x-hidden
            bg-gray-200
            xl:bg-white
            ${this.visible ? 'w-2/5' : ''}
            flex flex-col
            border
            xl:relative xl:w-full xl:h-full
            `"
    >
        <div
            v-if="this.followings.length > 0"
            class="w-full h-full flex flex-col"
        >
            <h4 class="w-full py-3 text-center text-lg border-b border-t">
                Đang theo dõi
            </h4>
            <ChatListItem
                v-for="(item, index) in this.followings"
                :key="index"
                :profile="item"
                v-on:itemClicked="this.toggleChatList"
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
            <h4 class="text-lg font-md p-4">Chưa theo dõi bất kỳ ai..</h4>
        </div>
        <div
            v-if="this.chatStrangers.length > 0"
            class="w-full h-full flex flex-col"
        >
            <h4 class="w-full py-3 text-center text-lg border-b border-t">
                Người lạ
            </h4>
            <Stranger
                v-for="(user, index) in this.chatStrangers"
                :key="index"
                :user="user"
                v-on:itemClicked="this.toggleChatList"
            />
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
                xl:hidden
            "
        >
            <i class="fas fa-times fa-2x"></i>
        </button>
    </div>
</template>

<script>
import ChatListItem from "./ChatListItem.vue";
import Stranger from "./Stranger.vue";
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
        strangers: Array,
    },
    computed: {
        ...mapGetters(["followings", "chatStrangers"]),
    },
    components: { ChatListItem, Stranger },
    methods: {
        ...mapMutations(["setFollowingsList", "setStrangers"]),
        toggleChatList() {
            this.visible = !this.visible;
        },
    },
    mounted() {
        this.setFollowingsList(this.chat_list);
        this.setStrangers(this.strangers);
    },
};
</script>
