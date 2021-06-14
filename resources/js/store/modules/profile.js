import axios from "axios";
const toast = {
    state() {
        return {
            validates: {},
            message: "",
            profile: {},
            followed: false,
        };
    },
    actions: {
        async editProfileAction({ commit }, { formData, username }) {
            try {
                const response = await axios.post(
                    `/profile/${username}`,
                    formData,
                    {
                        headers: {
                            "Content-Type": "multipart/form-data",
                        },
                    }
                );
                if (response.status == 200) {
                    commit("editProfileSuccess", response.data.profile);
                }
                return true;
            } catch (error) {
                commit("editProfileFail", error.response);
            }
            return false;
        },
        async toggleFollow({ commit }, following_id) {
            try {
                const response = await axios.post("/profile/follow", {
                    following_id,
                });
                commit("setFollowState", response.data.followed);
            } catch (error) {
                console.log(error.response);
                // commit("handleError", error.response);
            }
        },
    },
    mutations: {
        setFollowState(state, payload) {
            state.followed = payload;
        },
        handleError(state, payload) {},
        profileMutate(state, payload) {
            state.profile = { ...payload };
        },
        editProfileSuccess(state, payload) {
            state.profile = { ...payload };
        },
        editProfileFail(state, payload) {
            if (payload.status == 422) {
                state.validates = { ...payload.data.validates };
            } else {
                state.message = payload.data.message;
            }
        },
    },
    getters: {
        profile(state) {
            return state.profile;
        },
        profileValidates(state) {
            return state.validates;
        },
        profileMessage(state) {
            return state.message;
        },
        followed(state) {
            return state.followed;
        },
    },
};

export default toast;
