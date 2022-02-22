export default {
    state() {
        return {
            user: null,
            settings: [],
        };
    },
    actions: {
        async getCurUser({ state, commit }) {
            const response = await axios.get("/get-user").catch((error) => {
                commit("getUserActionFail", error.response);
            });
            commit("setUser", response.data);
        },
        async getUserSettings({ state, commit }, username) {
            const response = await axios
                .get(`/${username}/settings`)
                .catch((error) => console.log(error));
            if (response) {
                commit("setUserSettings", response.data);
            }
        },
    },
    mutations: {
        setUser(state, payload) {
            state.user = payload.user;
        },
        getUserActionFail(state, payload) {
            console.log(payload.data.message);
        },
        setUserSettings(state, payload) {
            state.settings = [...payload.settings];
        },
    },
    getters: {
        userGetter(state) {
            return state.user;
        },
        userSettings(state) {
            return state.settings;
        },
    },
};
