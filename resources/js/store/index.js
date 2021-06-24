import { createStore } from "vuex";
import toast from "./modules/toast";
import profile from "./modules/profile";
import auth from "./modules/auth";
import comment from "./modules/comment";
const store = createStore({
    modules: { toast, profile, auth, comment },
});
export default store;
