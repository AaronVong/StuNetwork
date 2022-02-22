<template>
    <button
        @click="this.toggleDialog"
        type="button"
        class="ml-auto mt-2 p-2 bg-white rounded-full border border-blue-400 text-blue-400 font-medium hover:bg-blue-100 transition-colors duration-200 focus:outline-none"
    >
        <i class="fas fa-cog"></i>Cài đặt
    </button>
    <el-dialog
        v-model="this.visible"
        width="50%"
        @opened="this.dialogOpened"
        title="Cài đặt"
    >
        <div class="grid grid-cols-5 w-full divide-x-2 h-96">
            <ul id="setting-menu" class="col-span-1 px-2 flex flex-col">
                <li
                    @click="this.toSection"
                    data-for-section="change-password"
                    class="w-full pill-hover text-lg py-3 text-center cursor-pointer"
                >
                    <span>Đổi mật khẩu</span>
                </li>
                <li
                    @click="this.toSection"
                    data-for-section="user-settings"
                    class="w-full pill-hover text-lg py-3 text-center cursor-pointer"
                >
                    <span> Cài đặt tin nhắn </span>
                </li>
            </ul>
            <div
                ref="setting-container"
                class="col-span-4 px-3 h-full overflow-auto"
            >
                <div
                    ref="change-password"
                    id="change-password"
                    class="w-full mb-3"
                >
                    <h4
                        class="font-bold text-xl py-3 underline text-black uppercase"
                    >
                        Thay đổi mật khẩu:
                    </h4>
                    <ChangePassword :visible="this.visible" />
                </div>
                <div ref="user-settings" id="user-settings" class="w-full mb-3">
                    <h4
                        class="font-bold text-xl py-3 underline text-black uppercase"
                    >
                        Cài đặt tin nhắn
                    </h4>
                    <ChatSettings
                        :username="this.username"
                        :isOpen="this.visible"
                    />
                </div>
            </div>
        </div>
        <template #footer>
            <span class="dialog-footer">
                <el-button @click="this.toggleDialog">Hủy bỏ </el-button>
            </span>
        </template>
    </el-dialog>
</template>
<script>
import ChangePassword from "../profile/ChangePassword.vue";
import ChatSettings from "./ChatSettings.vue";
export default {
    name: "UserSettings",
    data() {
        return {
            visible: false,
        };
    },
    props: {
        username: String,
    },
    methods: {
        toggleDialog() {
            this.visible = !this.visible;
        },
        dialogOpened() {
            const removeActiveClass = () => {
                $("ul#setting-menu li").removeClass("pill-hover-active");
            };
            const parent = this.$refs["setting-container"];
            const changePassword = $("li[data-for-section='change-password']");
            const userSettings = $("li[data-for-section='user-settings']");
            this.$refs["setting-container"].addEventListener("scroll", (e) => {
                if (parent.scrollTop <= changePassword.position().top) {
                    if (changePassword.hasClass("pill-hover-active")) return;
                    removeActiveClass();
                    changePassword.addClass("pill-hover-active");
                } else if (parent.scrollTop <= userSettings.position().top) {
                    if (userSettings.hasClass("pill-hover-active")) return;
                    removeActiveClass();
                    userSettings.addClass("pill-hover-active");
                }
            });
        },
        toSection(event) {
            const section = event.target.dataset.forSection;
            const target = this.$refs[section];
            let positionTop = target.offsetTop;
            if (section == "change-password") positionTop = 0;
            this.$refs["setting-container"].scroll({
                top: positionTop,
                behavior: "smooth",
            });
        },
    },
    components: {
        ChangePassword,
        ChatSettings,
    },
};
</script>
