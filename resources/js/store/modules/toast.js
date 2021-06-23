import axios from "axios";
export default {
    state() {
        return {
            toastList: [],
            page: 1,
            validates: {},
            errorMessage: "",
            infoMessage: "",
        };
    },
    actions: {
        // Lấy toast list đã phân trang
        async toastListPaginateAction({ commit, state }) {
            try {
                const response = await axios.get(`/toast?page=${state.page}`);
                if (response.status == 200) {
                    commit("toastListPaginateSuccess", response.data);
                }
            } catch (error) {
                commit("toastActionFail", error.response);
            }
            return;
        },
        /* Tạo mới Toast */
        async createToastAction({ commit }, formData) {
            try {
                const response = await axios.post("/toast", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                });
                if (response.status == 200) {
                    commit("createToastSuccess", response.data);
                    commit("toastActionSuccess", response.data);
                }
                return true;
            } catch (error) {
                commit("createToastFail", error.response);
            }
            return false;
        },
        /* Xóa Toast theo ID*/
        async deleteToastAction({ commit }, id) {
            try {
                const response = await axios.delete(`/toast/${id}`);
                if (response.status == 200) {
                    commit("deleteToastSuccess", response.data);
                    commit("toastActionSuccess", response.data);
                    return true;
                }
            } catch (error) {
                commit("toastActionFail", error.response);
            }
            return false;
        },
        // cập nhật toast
        async editToastAction({ commit }, { formData, id }) {
            try {
                const response = await axios.post(`/toast/${id}`, formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                });
                if (response.status == 200) {
                    commit("editToastSuccess", response.data);
                    commit("toastActionSuccess", response.data);
                }
                return response.data.updatedToast;
            } catch (error) {
                commit("toastActionFail", error.response);
            }
            return false;
        },
        /* Lấy Toast theo ID*/
        async getToastById({ commit }, id) {
            try {
                const response = await axios.get(`/toast/get/${id}`);
                if (response.status == 200) {
                    return response.data.toast;
                }
            } catch (error) {
                return null;
            }
        },
        /* Like Toast có với ID */
        async likeToastAction({ commit }, id) {
            try {
                const response = await axios.post("/toast/like", { id });
                commit("likeToastSuccess", response.data);
            } catch (error) {
                commit("toastActionFail", error.response);
            }
        },
        /* Lấy danh sách Toasts được upload bởi người dùng */
        async getToastListUploadedByUserId({ commit }, id) {
            try {
                const response = await axios.post("/toast/uploaded", {
                    user_id: id,
                });
                commit("setToastList", response.data.toasts);
                commit("setFollowingsList", response.data.followings);
            } catch (error) {
                commit("toastActionFail", error.response);
            }
        },
        /* Lấy danh sách Toasts được like bởi người dùng */
        async getToastListLikedByUserId({ commit }, id) {
            try {
                const response = await axios.post("/toast/liked", {
                    user_id: id,
                });
                commit("setToastList", response.data.toasts);
                commit("setFollowingsList", response.data.followings);
            } catch (error) {
                commit("toastActionFail", error.response);
            }
        },
    },
    mutations: {
        toastActionFail(state, payload) {
            if (payload.status == 422) {
                state.validates = { ...payload.data.validates };
            } else {
                state.errorMessage = payload.data.message;
            }
        },
        toastActionSuccess(state, payload) {
            state.infoMessage = payload.message;
        },
        setToastList(state, payload) {
            state.toastList = [...payload];
            state.page += 1;
        },
        // lấy danh sách toast đã phân trang
        toastListPaginateSuccess(state, payload) {
            state.toastList.push(...payload.toasts);
            state.page += 1;
        },
        // Thêm với toast thành công
        createToastSuccess(state, payload) {
            const regex = new RegExp("^(/toast)/[0-9]+$", "i");
            const result = regex.test(window.location.pathname);
            if (!result) {
                state.toastList.unshift(payload.createdToast);
            }
        },
        // Thêm mới toast thất bại
        createToastFail(state, payload) {
            if (payload.status == 422) {
                const validates = {
                    ...payload.data.validates,
                };
                const regex = new RegExp(/^(files_upload)\.[0-9]$/);
                for (const key in validates) {
                    if (regex.test(key)) {
                        state.validates = {
                            ...state.validates,
                            subfile: validates[key],
                        };
                    } else {
                        state.validates = {
                            ...state.validates,
                            [key]: validates[key],
                        };
                    }
                }
            }
            if (payload.status == 500) {
                state.message = payload.data.message;
            }
        },
        // xóa toast thành công
        deleteToastSuccess(state, payload) {
            const regex = new RegExp("^(/toast)/[0-9]+$", "i");
            const result = regex.test(window.location.pathname);
            if (result) {
                window.location.replace("/");
                return;
            }
            state.toastList = state.toastList.filter(
                (item) => item.id != payload.deletedID
            );
        },
        // cập nhật toast thành công
        editToastSuccess(state, payload) {
            state.toastList = state.toastList.map((toast, index) => {
                if (toast.id == payload.updatedToast.id) {
                    toast = { ...payload.updatedToast };
                }
                return toast;
            });
        },
        likeToastSuccess(state, payload) {
            state.toastList = state.toastList.map((item) => {
                if (item.id == payload.toastID) {
                    if (payload.likes.length <= 0) {
                        item.likes = [];
                    } else {
                        item.likes = [...payload.likes];
                    }
                }
                return item;
            });
        },
    },
    getters: {
        toastList(state) {
            return state.toastList;
        },
        toastValidates(state) {
            return state.validates;
        },
        toastInfoMessage(state) {
            return state.infoMessage;
        },
        toastValidates(state) {
            return state.validates;
        },
        toastErrorMessage(state) {
            return state.errorMessage;
        },
    },
};
