<template>
    <form
        class="w-full h-full p-4"
        :action="`${this.native_route}`"
        method="post"
        v-loading="this.loading"
        element-loading-text="Đang xử lý, vui lòng chở..."
        @submit="this.handleRegister"
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
        <div class="text-red-600 text-center mb-3">
            <!-- Register Status Here -->
            {{ this.message }}
        </div>
        <div class="flex flex-col mb-3">
            <label for="username" class="mb-3 text-lg font-bold text-gray-500"
                >Họ tên:
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
                id="fullname"
                name="fullname"
                placeholder="Nhập họ tên của bạn..."
                v-model="this.form.fullname"
            />
            <!-- Username Validation Error -->
            <span class="text-red-600" v-if="this.validates.fullname">
                {{ this.validates.fullname.shift() }}
            </span>
        </div>
        <div class="flex flex-col mb-3">
            <label for="username" class="mb-3 text-lg font-bold text-gray-500"
                >Tên tài khoản:
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
                id="username"
                name="username"
                placeholder="Nhập tên tài khoản..."
                v-model="this.form.username"
            />
            <!-- Username Validation Error -->
            <span class="text-red-600" v-if="this.validates.username">
                {{ this.validates.username.shift() }}
            </span>
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
            <!-- Email Validation Error -->
            <span class="text-red-600" v-if="this.validates.email">
                {{ this.validates.email.shift() }}
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
            <!-- Password Validation Error -->
            <span class="text-red-600" v-if="this.validates.password">
                {{ this.validates.password.shift() }}
            </span>
        </div>
        <div class="flex flex-col mb-3">
            <label
                for="password"
                class="mb-3 font-bold text-lg font-bold text-gray-500"
                >Nhập lại mật khẩu:
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
                id="password_confirmation"
                name="password_confirmation"
                placeholder="Nhập lại mật khẩu..."
                v-model="this.form.password_confirmation"
            />
            <!-- Password Confirmation Validation Error -->
            <span
                class="text-red-600"
                v-if="this.validates.password_confirmation"
            >
                {{ this.validates.password_confirmation.shift() }}
            </span>
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
                Đăng ký
            </button>
        </div>
        <div class="flex justify-center items-center mb-3 text-gray-600">
            <p>
                Đã có tài khoản?
                <a
                    :href="`${this.login_route}`"
                    class="underline text-blue-700 hover:text-blue-600 ml-2"
                >
                    Đăng nhập
                </a>
            </p>
        </div>
    </form>
</template>
<script>
import axios from "axios";
export default {
    name: "RegisterForm",
    data() {
        return {
            form: {
                fullname: "",
                username: "",
                email: "",
                password: "",
                password_confirmation: "",
            },
            validates: {},
            loading: false,
            message: "",
        };
    },
    props: {
        native_route: String,
        login_route: String,
    },
    methods: {
        async handleRegister(e) {
            e.preventDefault();
            this.loading = true;
            try {
                const response = await axios.post(
                    `${this.native_route}`,
                    { ...this.form },
                    {
                        headers: {
                            "Content-Type": "application/json",
                        },
                    }
                );
                if (response.status == 200) {
                    window.location.replace(`${response.data.next}`);
                }
            } catch (error) {
                if (error.response.status == 406) {
                    this.message = error.response.data.message;
                }
                if (error.response.status == 422) {
                    this.validates = { ...error.response.data.validates };
                }
            }
            this.loading = false;
        },
    },
};
</script>
