import { createStore } from "vuex";
import toast from "./modules/toast";
import profile from "./modules/profile";
import auth from "./modules/auth";
const store = createStore({
    modules: { toast, profile, auth },
});
export default store;
