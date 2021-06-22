import axios from "axios";
export default {
    state() {
        return {
            validates: {},
            errorMessage: "",
            infoMessage: "",
        };
    },
    actions: {
        async login({ commit }, { formData }) {
            try {
                const response = await axios.post("/login", formData, {
                    headers: {
                        "Content-Type": "application/json",
                    },
                });
                if (response.status == 200) {
                    if (response.data.next) {
                        window.location.replace(response.data.next);
                    }
                    commit("authActionSuccess", response.data);
                }
            } catch (error) {
                commit("authActionFail", error.response);
            }
            return;
        },
        async register({ commit }, { formData }) {
            try {
                const response = await axios.post("/register", formData, {
                    headers: {
                        "Content-Type": "application/json",
                    },
                });
                if (response.status == 200) {
                    if (response.data.next) {
                        window.location.replace(response.data.next);
                    }
                    commit("authActionSuccess", response.data);
                }
            } catch (error) {
                commit("authActionFail", error.response);
            }
            return;
        },
        async sendVerificationEmail({ commit }) {
            try {
                const response = await axios.post(
                    "/email/verification-notification"
                );
                if (response.status == 200) {
                    if (response.data.next) {
                        window.location.replace(response.data.next);
                    }
                    commit("authActionSuccess", response.data);
                }
            } catch (error) {
                commit("authActionFail", error.response);
            }
            return;
        },
        async resetPassword({ commit }, { formData }) {
            try {
                const response = await axios.post("/reset-password", formData);
                if (response.status == 200) {
                    if (response.data.next) {
                        alert(response.data.message);
                        window.location.replace(response.data.next);
                    }
                    commit("authActionSuccess", response.data);
                }
            } catch (error) {
                commit("authActionFail", error.response);
            }
            return;
        },
        async forgotPassword({ commit }, { formData }) {
            try {
                const response = await axios.post("/forgot-password", formData);
                if (response.status == 200) {
                    commit("authActionSuccess", response.data);
                }
            } catch (error) {
                commit("authActionFail", error.response);
            }
            return;
        },
    },
    mutations: {
        authActionSuccess(state, payload) {
            if (payload.message) {
                state.infoMessage = payload.message;
            }
        },
        authActionFail(state, payload) {
            if (payload.status == 422) {
                state.validates = { ...payload.data.validates };
            } else {
                state.errorMessage = payload.data.message;
            }
        },
    },
    getters: {
        authValidates(state) {
            return state.validates;
        },
        authErrorMessage(state) {
            return state.errorMessage;
        },
        authInfoMessage(state) {
            return state.infoMessage;
        },
    },
};
