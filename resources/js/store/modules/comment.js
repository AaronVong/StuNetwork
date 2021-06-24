import axios from "axios";
export default {
    state() {
        return {
            errorMessage: "",
            infoMessgae: "",
            validates: {},
            commentList: [],
            replyList: [],
        };
    },
    actions: {
        async commentAction({ commit }, { toast_id, content }) {
            try {
                const response = await axios.post(
                    `/toast/${toast_id}/comment`,
                    {
                        comment: content,
                    },
                    {
                        headers: {
                            "Content-Type": "application/json",
                        },
                    }
                );
                commit("commentActionSuccess", response.data.comment);
                return true;
            } catch (error) {
                commit("commentActionFail", error.response);
            }
            return false;
        },
        async replyAction({ commit }, { content, comment_id }) {
            try {
                const response = await axios.post(
                    `/comment/${comment_id}/reply`,
                    {
                        comment: content,
                    }
                );
                commit("commentActionSuccess", response.data.comment);
                return true;
            } catch (error) {
                commit("commentActionFail", error.response);
            }
            return false;
        },
    },
    mutations: {
        commentActionFail(state, payload) {
            if (payload.status == 422) {
                state.validates = { ...payload.data.validates };
            } else {
                state.errorMessage = payload.data.message;
            }
        },
        commentActionSuccess(state, payload) {
            if (payload.child_id == null) {
                state.commentList.push(payload);
            } else {
                state.replyList.push(payload);
            }
        },
        setCommentList(state, payload) {
            state.commentList = [...payload];
        },
        setReplyList(state, payload) {
            state.replyList = [...payload];
        },
    },
    getters: {
        commentErrorMessage(state) {
            return state.errorMessage;
        },
        commentInfoMessage(state) {
            return state.infoMessgae;
        },
        commentValidates(state) {
            return state.validates;
        },
        commentList(state) {
            return state.commentList;
        },
        replyList(state) {
            return state.replyList;
        },
    },
};
