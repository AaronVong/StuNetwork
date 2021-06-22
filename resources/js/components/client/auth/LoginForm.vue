<template>
    <form
        method="post"
        class="w-full h-full p-4"
        v-loading="this.loading"
        element-loading-text="Đang xử lý, vui lòng chở..."
        @submit="this.handleLogin"
    >
        <h1 class="text-4xl capitalize text-center py-2 text-gray-600">
            <span class="main-red">S</span>
            <span class="main-blue">T</span>
            <span class="main-red">U</span>
            <span class="text-lg">Network</span>
        </h1>
        <i class="text-sm block text-center text-gray-600">
            Sức khỏe - Trí tuệ - Ước vọng
        </i>
        <div class="text-red-600 text-center mb-3" v-if="this.authErrorMessage">
            {{ this.authErrorMessage }}
        </div>
        <div
            class="text-green-600 text-center mb-3 font-bold"
            v-if="this.authInfoMessage"
        >
            {{ this.authInfoMessage }}
        </div>
        <div class="flex flex-col mb-3">
            <label for="email" class="mb-3 text-lg font-bold text-gray-500"
                >Địa chỉ email:
            </label>
            <input
                class="
                    px-3
                    border
                    rounded-lg
                    h-12
                    mb-3
                    text-md
                    focus:outline-none
                    focus:ring focus:ring-1 focus:ring-blue-400
                "
                type="text"
                id="email"
                name="email"
                placeholder="Nhập địa chỉ email..."
                v-model="this.form.email"
            />
            <span class="text-red-500" v-if="this.authValidates.email">
                <!-- Safely get first element without changing state -->
                {{ [...this.authValidates.email].shift() }}
            </span>
        </div>
        <div class="flex flex-col mb-3">
            <label
                for="password"
                class="mb-3 font-bold text-lg font-bold text-gray-500"
                >Mật khẩu:
            </label>
            <input
                class="
                    px-3
                    border
                    rounded-lg
                    h-12
                    mb-3
                    text-md
                    focus:outline-none
                    focus:ring focus:ring-1 focus:ring-blue-400
                "
                type="password"
                id="password"
                name="password"
                placeholder="Nhập mật khẩu..."
                v-model="this.form.password"
            />
            <span class="text-red-500" v-if="this.authValidates.password">
                <!-- Safely get first element without changing state -->
                {{ [...this.authValidates.password].shift() }}
            </span>
        </div>
        <div class="flex items-center mb-3">
            <label
                for="remember"
                class="font-light text-normal text-gray-500 mr-3"
                >Ghi nhớ đăng nhập:
            </label>
            <input
                type="checkbox"
                name="remember"
                id="remember"
                v-model="this.form.remember"
            />
        </div>
        <div class="flex mb-3 justify-center items-center">
            <button
                type="submit"
                class="
                    rounded-full
                    w-32
                    py-3
                    bg-blue-500
                    hover:bg-blue-600
                    focus:outline-none
                    focus:ring focus:ring-1 focus:ring-blue-400
                    text-gray-200
                "
            >
                Đăng nhập
            </button>
        </div>
        <div class="flex justify-center items-center mb-3 text-gray-600">
            <p>
                Chưa có tài khoản?
                <a
                    :href="`${this.register_route}`"
                    class="underline text-blue-700 hover:text-blue-600 ml-2"
                >
                    Đăng ký
                </a>
            </p>
        </div>
        <div class="flex justify-center items-center mb-3 text-gray-600">
            <p>
                Quên mật khẩu?
                <a
                    :href="`${this.password_route}`"
                    class="underline text-blue-700 hover:text-blue-600 ml-2"
                >
                    Khôi phục mật khẩu
                </a>
            </p>
        </div>
    </form>
</template>
<script>
import { mapActions, mapGetters } from "vuex";
export default {
    name: "LoginForm",
    data() {
        return {
            form: {
                email: "",
                password: "",
                remember: false,
            },
            loading: false,
        };
    },
    computed: {
        ...mapGetters(["authValidates", "authErrorMessage", "authInfoMessage"]),
    },
    methods: {
        ...mapActions(["login"]),
        async handleLogin(e) {
            e.preventDefault();
            this.loading = true;
            await this.login({ formData: this.form });
            this.loading = false;
        },
    },
    props: {
        register_route: String,
        password_route: String,
    },
};
</script>
