<template>
    <div v-if="this.chatErrorMessage || !this.userChatWith">
        <h3 v-if="!this.userChatWith" class="text-2xl text-center p-6">
            Hãy chọn cuộc trò chuyện
        </h3>
        <h3
            v-else-if="this.chatErrorMessage"
            class="text-2xl text-center p-6 text-red-500"
        >
            {{ this.chatErrorMessage }}
        </h3>
    </div>
    <div v-else class="w-full h-full flex flex-col" v-loading="this.loading">
        <div class="w-full flex flex-col flex-grow h-80 border">
            <!-- Header -->
            <div class="border-b text-lgh-20 p-3">
                <div class="h-full flex items-center gap-2">
                    <div class="shirk-0 grow-0 w-16 rounded-full border">
                        <img
                            v-if="this.userChatWith.avatarUrl"
                            :src="this.userChatWith.avatarUrl"
                            class="block w-full rounded-full"
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
                            {{ this.userChatWith.fullname.split(" ").pop() }}
                        </div>
                    </div>
                    <a
                        :href="`/profile/${this.userChatWith.user.username}`"
                        class="hover:underline"
                    >
                        {{ this.userChatWith.fullname }}
                    </a>
                </div>
            </div>
            <!-- Messages Display-->
            <div id="messages-monitor" class="overflow-y-auto flex-grow h-80">
                <div v-if="this.loadMore" class="flex items-center px-3">
                    <img
                        src="/images/Pulse-1s-200px.svg"
                        class="w-full h-16 bg-transparent"
                    />
                </div>
                <Message
                    v-for="(message, index) in this.chatMessages"
                    :key="index"
                    :messagePackage="message"
                    :isSender="message.sender_id == this.user.id"
                    v-on:toBottom="this.scrollToBottom"
                />
                <div v-show="this.typing" class="flex items-center px-3">
                    <img
                        src="/images/Pulse-1s-197px.svg"
                        class="h-16 bg-transparent"
                    />
                    <i>{{ this.typingUser.username }} is typing</i>
                </div>
            </div>
        </div>
        <div class="w-full h-20 border">
            <ChatForm />
        </div>
    </div>
</template>

<script>
import Message from "./Message.vue";
import ChatForm from "./ChatForm.vue";
import { mapActions, mapGetters, mapMutations } from "vuex";
export default {
    name: "Chat",
    data() {
        return {
            loading: false,
            typing: false,
            typingUser: {},
            noMore: false,
            loadMore: false,
            showOldMessage: false,
        };
    },
    props: {
        user: Object,
    },
    computed: {
        ...mapGetters([
            "userChatWith",
            "chatMessages",
            "chatErrorMessage",
            "chatUser",
        ]),
    },
    watch: {
        userChatWith: {
            handler: async function (newVal, oldVal) {
                if (oldVal && newVal.user_id == oldVal.user_id) {
                    return;
                }
                this.loading = true;
                // load tất cả tin nhắn
                this.resetMessages();
                this.setMessagePage(1);
                this.noMore = false;
                this.showOldMessage = false;
                this.loadMore = false;
                await this.fetchMessagesAction(newVal.user_id);
                this.loading = false;
            },
            deep: true,
        },
    },
    components: { Message, ChatForm },
    methods: {
        ...mapMutations([
            "pushMessage",
            "setUser",
            "setMessagePage",
            "resetMessages",
        ]),
        ...mapActions(["fetchMessagesAction"]),
        scrollToBottom() {
            const element = $("#messages-monitor");
            const { scrollHeight } = element.get(0);
            element.scrollTop(scrollHeight);
        },
    },
    async mounted() {
        // await this.fetchMessagesAction(this.userChatWith.user_id);
        this.setUser(this.user);
        // nghe chanel của mình
        Echo.private(`stunetwork-chanel_${this.user.id}`)
            .listenForWhisper("typing", (e) => {
                if (e.user.id == this.userChatWith.user_id) {
                    this.typing = e.typing;
                    this.typingUser = e.user;
                }
            })
            .listen("MessageSent", (event) => {
                if (event.message.sender_id == this.userChatWith.user_id) {
                    this.pushMessage(event.message);
                    this.typing = false;
                }
            });
    },
    updated() {
        if (!this.showOldMessage) {
            this.scrollToBottom();
        }
        $("#messages-monitor").scroll(async (e) => {
            const { scrollTop, scrollHeight, clientHeight } = $(e.target).get(
                0
            );
            if (this.loadMore || this.noMore) {
                return;
            }
            if (scrollTop <= 50) {
                this.loadMore = true;
                this.showOldMessage = true;
                this.noMore = await this.fetchMessagesAction(
                    this.userChatWith.user_id
                );
            }
            this.loadMore = false;
        });
    },
};
</script>
