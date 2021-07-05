<template>
    <form
        @submit="this.handleSendMessage"
        class="w-full h-full flex items-center"
    >
        <div class="w-full h-full flex items-center">
            <input
                v-model="this.form.message"
                @keyup="this.handleTyping"
                type="text"
                placeholder="Tin nhắn..."
                class="w-full px-2 py-3 focus:outline-none"
            />
        </div>
        <div class="flex items-center justify-center w-20 h-full">
            <button
                type="submit"
                :class="`
                            flex
                            items-center
                            justify-center
                            h-1/2
                            w-1/2
                            rounded-full
                            bg-blue-500
                            text-gray-100
                            
                            ${
                                this.form.message
                                    ? 'hover:text-white'
                                    : 'btn-isDisabled'
                            }`"
            >
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </form>
</template>
<script>
import { mapActions, mapGetters } from "vuex";
export default {
    name: "ChatForm",
    data() {
        return {
            form: {
                message: "",
                receiver_id: null,
            },
        };
    },
    computed: {
        ...mapGetters([
            "chatValidates",
            "chatInfoMessage",
            "chatErrorMessage",
            "userChatWith",
            "chatUser",
        ]),
        typed() {
            return this.form.message.length > 0;
        },
    },
    components: {},
    methods: {
        ...mapActions(["sendMessageAction"]),
        async handleSendMessage(e) {
            e.preventDefault();
            if (!this.form.message) return;
            this.form.receiver_id = this.userChatWith.user_id;
            const ok = await this.sendMessageAction(this.form);
            if (ok) {
                Echo.private(
                    `stunetwork-chanel_${this.userChatWith.user_id}`
                ).whisper("sent", {
                    user: this.chatUser,
                    sent: true,
                    message: this.form.message,
                });
            }
            this.form.message = "";
        },
        handleTyping() {
            Echo.private(
                `stunetwork-chanel_${this.userChatWith.user_id}`
            ).whisper("typing", {
                user: this.chatUser,
                typing: this.form.message.length > 0,
            });
        },
    },
};
</script>
