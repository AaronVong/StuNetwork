import { createStore } from "vuex";
import toast from "./modules/toast";
import profile from "./modules/profile";
import auth from "./modules/auth";
import comment from "./modules/comment";
import chat from "./modules/chat";
const store = createStore({
    modules: { toast, profile, auth, comment, chat },
});
export default store;
