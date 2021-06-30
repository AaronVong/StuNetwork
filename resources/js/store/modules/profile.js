import axios from "axios";
const toast = {
    state() {
        return {
            validates: {},
            profile: {},
            followed: false,
            followings: [],
            errorMessage: "",
            infoMessage: "",
        };
    },
    actions: {
        // Cập nhật profile
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
                    commit("editProfileSuccess", response.data);
                }
                return true;
            } catch (error) {
                commit("profileActionFail", error.response);
            }
            return false;
        },
        // toggle follow profile
        async toggleFollow({ commit }, profile_id) {
            try {
                const response = await axios.post("/profile/follow", {
                    profile_id,
                });
                commit("toggleFollowSucces", response.data);
            } catch (error) {
                commit("profileActionFail", error.response);
            }
        },
        // Lấy danh sách pprofile đã được follow bởi user
        async getProfilesFollowedByUserId({ commit }, id) {
            try {
                const response = await axios.post("/proifle/followings", {
                    id,
                });
                commit("setFollowingsList", response.data.followings);
            } catch (error) {
                commit("profileActionFail", error.response);
            }
        },
        // kiểm tra đã follow profile hay chưa
        async isFollowed({ commit }, profile_id) {
            try {
                const response = await axios.post("/profile/followed", {
                    profile_id,
                });
                commit("setFollowState", response.data.followed);
            } catch (error) {
                commit("profileActionFail", error.response);
            }
        },
    },
    mutations: {
        setFollowingsList(state, payload) {
            state.followings = [...payload];
        },
        toggleFollowSucces(state, payload) {
            state.followed = payload.followed;
            state.followings = state.followings.filter(
                (item) => item.id != payload.profileId
            );
        },
        setFollowState(state, payload) {
            state.followed = payload;
        },
        setProfile(state, payload) {
            state.profile = { ...payload };
        },
        editProfileSuccess(state, payload) {
            state.profile = { ...payload.profile };
            state.infoMessage = payload.message;
        },
        profileActionFail(state, payload) {
            if (payload.status == 422) {
                state.validates = payload.data.validates;
            } else {
                state.errorMessage = payload.data.message;
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
        followings(state) {
            return state.followings;
        },
        profileErrorMessage(state) {
            return state.errorMessage;
        },
        profileInfoMessage(state) {
            return state.infoMessage;
        },
    },
};

export default toast;
