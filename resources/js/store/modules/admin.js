import axios from "axios";
export default {
    state() {
        return {
            errorMessage: "",
            infoMessage: "",
            validates: [],
        };
    },
    actions: {
        async loginPermission({ commit }, username) {
            try {
                const response = await axios.post(
                    `/list-users/${username}/loginpermission`
                );
                commit("loginPermissionSuccess", response.data);
                return response.data.revoke;
            } catch (error) {
                commit("adminActionFail", error.response);
            }
            // fail
            return null;
        },
        async editUserAccount({ commit }, { permissions, username }) {
            try {
                const response = await axios.post(
                    `/list-users/${username}/edit`,
                    { updatedPermissions: { ...permissions } },
                    {
                        headers: {
                            "Content-Type": "application/json",
                        },
                    }
                );
                commit("editUserAccountSuccess", response.data);
            } catch (error) {
                commit("adminActionFail", error.response);
            }
        },
    },
    mutations: {
        adminActionFail(state, payload) {
            if (payload.status == 422) {
                state.validates = [...payload.data.validates];
            } else {
                state.errorMessage = payload.data.message;
            }
        },
        loginPermissionSuccess(state, payload) {
            state.infoMessage = payload.message;
        },
        editUserAccountSuccess(state, payload) {
            alert(payload.message);
            window.location.reload();
        },
    },
    getters: {
        adminErrorMessage(state) {
            return state.errorMessage;
        },
        adminInfoMessage(state) {
            return state.infoMessage;
        },
        adminValidates(state) {
            return state.validates;
        },
    },
};
