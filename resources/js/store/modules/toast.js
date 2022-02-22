import axios from "axios";
export default {
    state() {
        return {
            toastList: [],
            page: 1,
            validates: {},
            errorMessage: null,
            infoMessage: "",
        };
    },
    actions: {
        // Lấy toast list đã phân trang
        async toastListPaginateAction({ commit, state }) {
            try {
                let path = `/toast?page=${state.page}`;
                const regex = new RegExp("^(/home-other)$", "i");
                const result = regex.test(window.location.pathname);
                if (result) {
                    // nếu đang trang chi tiết toast không chạy infinite scroll
                    path = `home-other?page=${state.page}`;
                }
                const response = await axios.get(path);
                if (response.status == 200) {
                    commit("toastListPaginateSuccess", response.data);
                    return false;
                }
            } catch (error) {
                commit("toastActionFail", error.response);
            }
            return true;
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
                return response.data.liked;
            } catch (error) {
                commit("toastActionFail", error.response);
                return null;
            }
        },
        /* Lấy danh sách Toasts được upload bởi người dùng */
        async getToastListUploadedByUserId({ commit, state }, id) {
            try {
                const response = await axios.post(
                    `/toast/uploaded?page=${state.page}`,
                    {
                        user_id: id,
                    }
                );
                if (response.status == 200) {
                    commit("toastListPaginateSuccess", response.data);
                    // commit("setFollowingsList", response.data.followings);
                    return false;
                }
            } catch (error) {
                commit("toastActionFail", error.response);
            }
            return true;
        },
        /* Lấy danh sách Toasts được like bởi người dùng */
        async getToastListLikedByUserId({ commit, state }, id) {
            try {
                const response = await axios.post(
                    `/toast/liked?page=${state.page}`,
                    {
                        user_id: id,
                    }
                );
                if (response.status == 200) {
                    commit("toastListPaginateSuccess", response.data);
                    // commit("setFollowingsList", response.data.followings);
                    return false;
                }
            } catch (error) {
                commit("toastActionFail", error.response);
            }
            return true;
        },
    },
    mutations: {
        setPage(state, payload = 1) {
            state.page = payload;
        },
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
            if (!payload) {
                // nếu truyền mảng rỗng => reset
                state.toastList = [];
            } else {
                state.toastList = [...payload];
                state.page += 1;
            }
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
                state.infoMessage = payload.message;
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
            } else {
                state.errorMessage = payload.data.message;
            }
        },
        // xóa toast thành công
        deleteToastSuccess(state, payload) {
            const regex = new RegExp("^(/toast)/[0-9]+$", "i");
            const result = regex.test(window.location.pathname);
            state.infoMessage = payload.message;
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
                    item.likesCount = payload.likes.length;
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
