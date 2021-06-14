<template>
    <form
        method="post"
        v-loading="this.loading"
        element-loading-text="Đang xử lý, vui lòng chở..."
        @submit="this.handleSubmit"
    >
        <div class="text-red-600 text-center mb-3" v-if="this.message">
            <span>{{ this.message }}</span>
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
            <span class="text-red-500" v-if="this.validates.email">
                {{ this.validates.email.shift() }}
            </span>
        </div>
        <div class="flex mb-3 justify-center items-center">
            <button
                type="submit"
                class="
                    rounded-full
                    p-2
                    bg-blue-500
                    hover:bg-blue-600
                    focus:outline-none
                    focus:ring focus:ring-1 focus:ring-blue-400
                    text-gray-200
                "
            >
                Khôi phục mật khẩu
            </button>
        </div>
    </form>
    <p class="mb-3 font-normal text-lg md:text-black">
        <span class="underline mr-2">Lưu ý:</span>Nếu không tìm thấy email trong
        hộp thư, hãy kiểm tra <span class="text-yellow-500">hộp thư rác</span>.
    </p>
</template>

<script>
import axios from "axios";
export default {
    name: "ForgotPasswordForm",
    data() {
        return {
            form: {
                email: "",
            },
            validates: {},
            message: null,
            loading: false,
        };
    },
    props: {
        native_route: String,
    },
    methods: {
        async handleSubmit(e) {
            e.preventDefault();
            this.loading = true;
            try {
                const response = await axios.post(this.native_route, {
                    ...this.form,
                });
                this.message = response.data.message;
            } catch (error) {
                if (error.response.status == 422) {
                    this.validates = {
                        ...error.response.data.validates,
                    };
                }

                if (error.response.status == 404) {
                    this.message = error.response.data.message;
                }

                if (error.response.status == 500) {
                    this.message = "Hệ thống đang bận, vui lòng thử lại sau";
                }
            }
            this.loading = false;
        },
    },
};
</script>
