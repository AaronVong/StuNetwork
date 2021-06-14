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
        async login({ commit }, { formData, url }) {
            try {
                const response = await axios.post(
                    url,
                    { ...formData },
                    {
                        headers: {
                            "Content-Type": "application/json",
                        },
                    }
                );
                if (response.status == 200 && response.data.status == true) {
                    window.location.replace(response.data.next);
                }
            } catch (error) {
                commit("handleError", error.response);
            }
        },
    },
    mutations: {
        handleError(state, payload) {
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
