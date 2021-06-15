import axios from "axios";
export default {
    state() {
        return {
            toastList: [],
            page: 1,
            validates: {},
            message: "",
        };
    },
    actions: {
        // Lấy toast list đã phân trang
        async toastListPaginateAction({ commit, state }) {
            try {
                const response = await axios.get(`/toast?page=${state.page}`);
                if (response.status == 200) {
                    commit("toastListPaginateMutate", response.data.toasts);
                }
            } catch (error) {
                console.log(error.response);
            }
        },
        // Thêm mới toast
        async createToastAction({ commit }, formData) {
            try {
                const response = await axios.request({
                    method: "POST",
                    url: "/toast",
                    data: formData,
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                });
                if (response.status == 200) {
                    commit("createToastSuccess", response.data.toast);
                }
                return true;
            } catch (error) {
                commit("createToastFail", error.response);
            }
            return false;
        },
        // xóa toast
        async deleteToastAction({ commit }, id) {
            try {
                const response = await axios.delete(`/toast/${id}`);
                if (response.status == 200) {
                    commit("deleteToastSuccess", response.data.toastID);
                    return true;
                }
            } catch (error) {
                commit("deleteToastFail", error.response.data.message);
            }
            return false;
        },
        // lấy toast theo id
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
        // cập nhật toast
        async editToastAction({ commit }, { formData, id }) {
            try {
                const response = await axios.post(`/toast/${id}`, formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                });
                if (response.status == 200) {
                    commit("editToastSuccess", response.data.toast);
                }
                return response.data.toast;
            } catch (error) {
                commit("editToastFail", error.response);
            }
            return false;
        },
        async likeToastAction({ commit }, id) {
            try {
                const response = await axios.post("/toast/like", { id });
                commit("likeToastSuccess", response.data);
            } catch (error) {
                console.log(error.response);
            }
        },

        async getToastListUploadedByUserId({ commit }, id) {
            try {
                const response = await axios.post("/toast/uploaded", {
                    user_id: id,
                });
                commit("setToastList", response.data.toasts);
            } catch (error) {
                console.log(error);
            }
        },
        async getToastListLikedByUserId({ commit }, id) {
            try {
                const response = await axios.post("/toast/liked", {
                    user_id: id,
                });
                commit("setToastList", response.data.toasts);
            } catch (error) {
                console.log(error);
            }
        },
    },
    mutations: {
        setToastList(state, payload) {
            state.toastList = [...payload];
        },
        // lấy danh sách toast đã phân trang
        toastListPaginateMutate(state, payload) {
            state.toastList = [...state.toastList, ...payload];
            state.page += 1;
        },
        // Thêm với toast thành công
        createToastSuccess(state, payload) {
            state.toastList.unshift(payload);
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
            state.toastList = state.toastList.filter(
                (item) => item.id != payload
            );
        },
        // xóa toast thất bại
        deleteToastFail(state, payload) {
            state.message = payload;
        },
        // cập nhật toast thành công
        editToastSuccess(state, payload) {
            state.toastList = state.toastList.map((toast, index) => {
                if (toast.id == payload.id) {
                    toast = { ...payload };
                }
                return toast;
            });
        },
        // cập nhật toast thất bại
        editToastFail(state, payload) {
            if (payload.status == 422) {
                state.validates = { ...payload.data.validates };
            }

            if (payload.status == 404) {
                state.message = payload.data.message;
            }
        },
        likeToastSuccess(state, payload) {
            state.toastList = state.toastList.map((item) => {
                if (item.id == payload.toast_id) {
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
        message(state) {
            return state.message;
        },
        validates(state) {
            return state.validates;
        },
    },
};
