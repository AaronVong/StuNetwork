require("./bootstrap");
import { createApp } from "vue";
import store from "./store/index";
/*
|
| Element plus
|
*/
import {
    ElDialog,
    ElLoading,
    ElButton,
    ElPopover,
    ElUpload,
    ElMessage,
    ElSkeleton,
    ElSkeletonItem,
    ElPopconfirm,
    ElMessageBox,
    ElTabPane,
    ElTabs,
    ElInfiniteScroll,
} from "element-plus";
import "../scss/element-variables.scss";
import "../scss/heart_animation.scss";
/*
|
| Vue New Components
|
*/
import RegisterForm from "./components/client/auth/RegisterForm";
import LoginForm from "./components/client/auth/LoginForm";
import VerifyEmailForm from "./components/client/auth/VerifyEmailForm";
import ToastForm from "./components/client/toast/ToastForm";
import ForgotPasswordForm from "./components/client/auth/ForgotPasswordForm";
import ResetPasswordForm from "./components/client/auth/ResetPasswordForm";
import QuickToast from "./components/client/toast/QuickToast";
import ToastList from "./components/client/toast/ToastList";
import Profile from "./components/client/profile/Profile";
import Toast from "./components/client/toast/Toast";
import Comments from "./components/client/comment/Comments";
import ChatList from "./components/client/chat/ChatList";
const app = createApp({
    components: {
        "register-form": RegisterForm,
        "login-form": LoginForm,
        "verify-email-form": VerifyEmailForm,
        "toast-form": ToastForm,
        "forgot-password-form": ForgotPasswordForm,
        "reset-password-form": ResetPasswordForm,
        "quick-toast": QuickToast,
        "toast-list": ToastList,
        toast: Toast,
        profile: Profile,
        "toast-comments": Comments,
        "chat-list": ChatList,
    },
});

app.use(ElUpload);
app.use(ElDialog);
app.use(ElLoading);
app.use(ElButton);
app.use(ElPopover);
app.use(ElMessage);
app.use(ElSkeleton);
app.use(ElSkeletonItem);
app.use(ElPopconfirm);
app.use(ElMessageBox);
app.use(ElTabPane);
app.use(ElTabs);
app.use(ElInfiniteScroll);
app.use(store);
app.mount("#app");

/* App Script */
