require("./bootstrap");
import { createApp } from "vue";
import store from "./store/index";
/*
|
| Socket.io
|
*/
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
    ElNotification,
    ElTooltip,
    ElTable,
    ElTableColumn,
} from "element-plus";
import "../scss/element-variables.scss";
import "../scss/heart_animation.scss";
/*
|
| Vue Components
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
import Chat from "./components/client/chat/Chat";
import AdminNav from "./components/admin/AdminNav";
import AdminInfo from "./components/admin/AdminInfo";
import MemberList from "./components/admin/MemberList";
import ListUsers from "./components/admin/ListUsers";
import ListToasts from "./components/admin/ListToasts";
import UserDetail from "./components/admin/UserDetail";
import Search from "./components/client/search/Search";
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
        "chat-app": Chat,
        "admin-nav": AdminNav,
        "admin-info": AdminInfo,
        "member-list": MemberList,
        "list-users": ListUsers,
        "user-detail": UserDetail,
        "list-toasts": ListToasts,
        search: Search,
    },
    async mounted() {
        const user = async () => {
            try {
                const response = await axios.get("/get-user");
                const user = response.data.user;
                Echo.private(`stunetwork-chanel_${user.id}`).listenForWhisper(
                    "sent",
                    (e) => {
                        this.$notify({
                            type: "info",
                            message: e.message,
                            title: `${e.user.username} nhắn cho bạn`,
                        });
                    }
                );
            } catch (error) {
                return null;
            }
        };
        const regex = new RegExp("^(/chat).*$", "i");
        const result = regex.test(window.location.pathname);
        if (!result) {
            await user();
        }
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
app.use(ElNotification);
app.use(ElTooltip);
app.use(ElTable);
app.use(ElTableColumn);
app.use(store);
app.mount("#app");

/* App Script */
import "jquery-ui/ui/widgets/draggable";
$(document).ready(() => {
    $("#chat-list-drag").draggable();
});
