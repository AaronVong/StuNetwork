<template>
    <form
        method="post"
        class="w-full h-full p-4"
        @submit="this.handleSubmit"
        v-loading="this.loading"
        element-loading-text="Đang xử lý, vui lòng chở..."
    >
        <div class="text-red-600 text-center mb-3" v-if="this.authErrorMessage">
            <span>{{ this.authErrorMessage }}</span>
        </div>
        <div
            class="text-green-600 text-center mb-3 font-bold"
            v-if="this.authInfoMessage"
        >
            <span>{{ this.authInfoMessage }}</span>
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
                {{ [...this.authValidates.password].shift() }}
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
            <span
                class="text-red-500"
                v-if="this.authValidates.password_confirmation"
            >
                {{ [...this.authValidates.password_confirmation].shift() }}
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
                Khôi phục
            </button>
        </div>
    </form>
</template>
<script>
import { mapActions, mapGetters } from "vuex";
export default {
    name: "ResetPasswordForm",
    data() {
        return {
            form: {
                email: "",
                password: "",
                password_confirmation: "",
                token: "",
            },
            loading: false,
        };
    },
    props: {
        token: String,
    },
    computed: {
        ...mapGetters(["authValidates", "authErrorMessage", "authInfoMessage"]),
    },
    methods: {
        ...mapActions(["resetPassword"]),
        async handleSubmit(e) {
            e.preventDefault();
            this.loading = true;
            this.form.token = this.token;
            await this.resetPassword({ formData: this.form });
            this.loading = false;
            this.form.password = "";
            this.form.password_confirmation = "";
        },
    },
};
</script>
