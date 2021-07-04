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
            } catch (error) {
                commit("chatActionFail", error.response);
            }
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
            state.messages = [...payload.messages, ...state.messages];
            state.page += 1;
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
