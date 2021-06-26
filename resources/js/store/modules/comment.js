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

        async deleteCommentAction({ commit }, comment_id) {
            try {
                const response = await axios.delete(`/comment/${comment_id}`);
                commit("deleteCommentSuccess", response.data);
                return true;
            } catch (error) {
                commit("commentActionFail", error.response);
            }
            return false;
        },

        async updateCommentAction({ commit }, { content, comment_id }) {
            try {
                const response = await axios.post(
                    `/comment/${comment_id}`,
                    {
                        comment: content,
                        _method: "PUT",
                    },
                    {
                        headers: {
                            "Content-Type": "application/json",
                        },
                    }
                );
                commit("updateCommentSuccess", response.data);
                return response.status;
            } catch (error) {
                commit("commentActionFail", error.response);
                return error.response.status;
            }
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
        deleteCommentSuccess(state, payload) {
            if (payload.comment.child_id == null) {
                const index = state.commentList.findIndex(
                    (comment) => comment.id == payload.comment.id
                );
                if (index > -1) {
                    state.commentList.splice(index, 1);
                }
            } else {
                const index = state.replyList.findIndex(
                    (comment) => comment.id == payload.comment.id
                );
                if (index > -1) {
                    state.replyList.splice(index, 1);
                }
            }
            state.infoMessgae = payload.message;
        },
        updateCommentSuccess(state, payload) {
            if (payload.comment.child_id == null) {
                const index = state.commentList.findIndex(
                    (comment) => comment.id == payload.comment.id
                );
                if (index > -1) {
                    state.commentList[index].comment = payload.comment.comment;
                }
            } else {
                const index = state.replyList.findIndex(
                    (comment) => comment.id == payload.comment.id
                );
                if (index > -1) {
                    state.replyList[index].comment = payload.comment.comment;
                }
            }
            state.infoMessgae = payload.message;
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
