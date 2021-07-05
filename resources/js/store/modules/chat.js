import axios from "axios";
export default {
    state() {
        return {
            validates: {},
            infoMessage: "",
            errorMessage: "",
            chatWith: null, // profile with users relationships
            user: null,
            messages: [],
            page: 1,
        };
    },
    actions: {
        async sendMessageAction({ commit, dispatch }, dataForm) {
            try {
                const response = await axios.post(
                    `/messages/${dataForm.receiver_id}`,
                    dataForm,
                    {
                        "Content-Type": "multipart/form-data",
                    }
                );
                commit("sendMessagesSuccess", response.data);
                return true;
            } catch (error) {
                commit("chatActionFail", error.response);
            }
            return false;
        },
        async fetchMessagesAction({ commit, state }, receiver_id) {
            try {
                const response = await axios.get(
                    `/messages/${receiver_id}?page=${state.page}`
                );
                if (response.status == 200) {
                    commit("fetchMessagesSuccess", response.data);
                    return false;
                }
            } catch (error) {
                commit("chatActionFail", error.response);
            }
            return true;
        },

        async deleteMessageAction(
            { commit, state },
            { message_id, receiver_id }
        ) {
            try {
                const response = await axios.delete(
                    `/messages/${receiver_id}/${message_id}`
                );
                if (response.status == 200) {
                    commit("deleteMessagesSuccess", response.data);
                    Echo.private(
                        `stunetwork-chanel_${response.data.receiver.id}`
                    ).whisper("delete-message", {
                        message_id: response.data.message_id,
                        sender: state.user,
                    });
                    return true;
                }
            } catch (error) {
                commit("chatActionFail", error.response);
            }
            return false;
        },
    },
    mutations: {
        chatActionFail(state, payload) {
            if (payload.status == 422) {
                state.validates = { ...payload.validates };
            } else {
                state.errorMessage = payload.errorMessage;
            }
        },
        sendMessagesSuccess(state, payload) {
            state.messages.push(payload.message);
        },
        fetchMessagesSuccess(state, payload) {
            let array = [...payload.messages];
            state.messages.unshift(...array.reverse());
            state.page += 1;
        },
        deleteMessagesSuccess(state, payload) {
            state.messages = state.messages.map((item) => {
                if (item.id == payload.message_id) {
                    item.deleted = true;
                    item.message = "Tin nhắn đã bị xóa";
                }
                return item;
            });
        },
        changeChatWith(state, payload) {
            state.chatWith = { ...payload };
        },
        pushMessage(state, payload) {
            state.messages.push(payload);
        },
        setUser(state, payload) {
            state.user = { ...payload };
        },
        setMessagePage(state, payload = 1) {
            state.page = payload;
        },
        resetMessages(state, payload) {
            state.messages = [];
        },
    },
    getters: {
        chatValidates(state) {
            return state.validates;
        },
        chatInfoMessage(state) {
            return state.infoMessage;
        },
        chatErrorMessage(state) {
            return state.errorMessage;
        },
        userChatWith(state) {
            return state.chatWith;
        },
        chatMessages(state) {
            return state.messages;
        },
        chatUser(state) {
            return state.user;
        },
        chatPage(state) {
            return state.page;
        },
    },
};
