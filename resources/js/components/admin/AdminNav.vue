<template>
    <nav
        id="admin-nav"
        ref="admin-nav"
        class="
            relative
            w-20
            h-full
            bg-gray-900
            transition-width
            lg:w-60
            col-span-1
        "
    >
        <ul
            class="
                sticky
                top-0
                left-0
                list-none
                w-full
                flex flex-col
                items-center
                text-gray-400
                p-6
                md:items-start
            "
        >
            <li class="w-full">
                <div
                    type="button"
                    @click.prevent="this.toggleNav"
                    :class="`
                        hidden
                        w-full
                        h-12
                        lg:flex
                        gap-3
                        items-center
                        justify-center
                        cursor-pointer
                        hover:bg-white hover:text-gray-900
                        transition-colors
                        md:px-3 ${
                            !this.showNavItem
                                ? 'md:justify-center'
                                : 'md:justify-start'
                        }`"
                >
                    <i class="fas fa-align-justify"></i>
                </div>
            </li>
            <li class="w-full">
                <div
                    type="button"
                    :class="`
                        relative
                        w-full
                        h-12
                        cursor-pointer
                        transition-colors
                        hover:text-gray-200
                        md:hover:bg-gray-200 md:hover:text-gray-900
                        md:px-3`"
                >
                    <AdminNavTooltip
                        text="Toast"
                        :visible="!this.showNavItem"
                    />
                    <a
                        href="/dashboard#list-toasts"
                        :class="`w-full h-full flex gap-3 items-center ${
                            !this.showNavItem
                                ? 'justify-center'
                                : 'justify-start'
                        }`"
                    >
                        <i class="fas fa-bread-slice"></i>
                        <span
                            v-show="this.showNavItem"
                            class="hidden lg:inline-block text-lg"
                            >Toasts</span
                        >
                    </a>
                </div>
            </li>
            <li class="w-full">
                <div
                    :class="`
                    relative
                        w-full
                        h-12
                        cursor-pointer
                        transition-colors
                        hover:text-gray-200
                        md:hover:bg-gray-200 md:hover:text-gray-900
                        md:px-3`"
                >
                    <AdminNavTooltip
                        text="Người dùng"
                        :visible="!this.showNavItem"
                    />
                    <a
                        href="/dashboard#list-users"
                        :class="`w-full h-full flex gap-3 items-center ${
                            !this.showNavItem
                                ? 'justify-center'
                                : 'justify-start'
                        }`"
                    >
                        <i class="fas fa-users"></i>
                        <span
                            v-show="this.showNavItem"
                            class="hidden lg:inline-block"
                            >Người dùng</span
                        >
                    </a>
                </div>
            </li>
            <li class="w-full">
                <div
                    :class="`
                    relative
                        w-full
                        h-12
                        cursor-pointer
                        transition-colors
                        hover:text-gray-200
                        md:hover:bg-gray-200 md:hover:text-gray-900
                        md:px-3`"
                >
                    <AdminNavTooltip
                        text="Quản trị"
                        :visible="!this.showNavItem"
                    />
                    <a
                        href="/dashboard#list-members"
                        :class="`w-full h-full flex gap-3 items-center ${
                            !this.showNavItem
                                ? 'justify-center'
                                : 'justify-start'
                        }`"
                    >
                        <i class="fas fa-users-cog"></i>
                        <span
                            v-show="this.showNavItem"
                            class="hidden lg:inline-block"
                            >Quản trị</span
                        >
                    </a>
                </div>
            </li>
            <li class="w-full">
                <div
                    :class="`
                    relative
                        w-full
                        h-12
                        cursor-pointer
                        transition-colors
                        hover:text-gray-200
                        md:hover:bg-gray-200 md:hover:text-gray-900
                        md:px-3`"
                >
                    <AdminNavTooltip
                        text="Đến trang web"
                        :visible="!this.showNavItem"
                    />
                    <a
                        :href="this.publicPath"
                        target="_blank"
                        :class="`w-full h-full flex gap-3 items-center ${
                            !this.showNavItem
                                ? 'justify-center'
                                : 'justify-start'
                        }`"
                    >
                        <i class="fas fa-external-link-alt"></i>
                        <span
                            v-show="this.showNavItem"
                            class="hidden lg:inline-block"
                        >
                            Đến trang web
                        </span>
                    </a>
                </div>
            </li>
            <li class="w-full">
                <div
                    :class="`
                    relative
                        w-full
                        h-12
                        cursor-pointer
                        transition-colors
                        bg-red-600
                        text-white
                        hover:bg-red-700
                        hover:text-gray-100
                        md:px-3`"
                >
                    <AdminNavTooltip
                        text="Đăng xuất"
                        :visible="!this.showNavItem"
                    />
                    <a
                        @click="this.logout"
                        href=""
                        :class="`w-full h-full flex gap-3 items-center ${
                            !this.showNavItem
                                ? 'justify-center'
                                : 'justify-start'
                        }`"
                    >
                        <i class="fas fa-sign-out-alt"></i>
                        <span
                            v-show="this.showNavItem"
                            class="hidden lg:inline-block"
                            >Đăng xuất</span
                        >
                    </a>
                </div>
            </li>
        </ul>
    </nav>
</template>

<script>
import AdminNavTooltip from "./AdminNavTooltip.vue";
import process from "process";
import axios from "axios";
export default {
    name: "AdminNav",
    data() {
        return {
            showNavItem: false,
            screenWidth: null,
        };
    },
    computed: {
        publicPath() {
            return process.env.MIX_PUBLIC_PATH;
        },
    },
    components: { AdminNavTooltip },
    methods: {
        toggleNav() {
            this.showNavItem = !this.showNavItem;
            $(this.$refs["admin-nav"]).toggleClass("lg:w-60");
        },
        async logout(e) {
            e.preventDefault();
            const loading = this.$loading({
                fullscreen: true,
            });
            try {
                const response = await axios.post("/logout");
                if (response.status == 200) {
                    window.location.replace(
                        process.env.MIX_PUBLIC_PATH + "/login"
                    );
                }
            } catch (error) {
                console.log(error);
                loading.close();
            }
        },
    },
    mounted() {
        this.screenWidth = document.documentElement.clientWidth;
        if (this.screenWidth >= 1024) {
            this.showNavItem = true;
        }
        $(window).resize(() => {
            this.screenWidth = document.documentElement.clientWidth;
            if (this.screenWidth >= 1024) {
                this.showNavItem = true;
                $(this.$refs["admin-nav"]).addClass("lg:w-60");
            } else if (this.screenWidth < 1024) {
                this.showNavItem = false;
                $(this.$refs["admin-nav"]).removeClass("lg:w-60");
            }
        });
    },
};
window;
</script>
