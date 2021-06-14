<template>
    <div
        class="w-full bg-white text-center rounded-xl p-2"
        v-loading="this.loading"
        element-loading-text="Đang xử lý, vui lòng chở..."
    >
        <h2 class="text-2xl mb-3 py-2 font-medium bg-gray-600 text-white">
            Yêu cầu xác thực email
        </h2>
        <p class="mb-3">
            Để hoàn tất việc đăng ký, bạn cần xác thực email của mình, hãy truy
            cập email và click vào đường dẫn mà chúng tôi đã gửi, để hoàn thành
            đăng ký và kích hoạt tài khoản.
        </p>
        <form
            :action="`${this.native_route}`"
            method="post"
            @submit="this.handleSendEmail"
        >
            <div class="flex mb-3 justify-center items-center">
                <label
                    for="password"
                    class="mr-3 font-bold text-lg md:text-gray-500"
                    >Không nhận được mail ?
                </label>
                <button
                    type="submit"
                    class="
                        rounded-sm
                        w-auto
                        p-2
                        bg-green-500
                        text-gray-100
                        hover:bg-green-600
                        focus:outline-none
                        focus:ring focus:ring-2 focus:ring-green-400
                    "
                >
                    Gửi lại mail cho tôi
                </button>
            </div>
            <div class="text-red-600 text-center" v-if="this.status === true">
                {{ this.message }}
            </div>
        </form>
        <p class="mb-3 font-normal text-lg md:text-black">
            <span class="underline mr-2">Lưu ý:</span>Nếu không tìm thấy email
            trong hộp thư, hãy kiểm tra
            <span class="text-yellow-500">hộp thư rác</span>.
        </p>
    </div>
</template>

<script>
import axios from "axios";
export default {
    name: "VerifyEmailForm",
    data() {
        return {
            status: "",
            message: "",
            loading: false,
        };
    },
    methods: {
        async handleSendEmail(e) {
            e.preventDefault();
            this.loading = true;
            try {
                const response = await axios.post(`${this.native_route}`);
                if (response.status == 200) {
                    if (response.data.status == true) {
                        this.status = response.data.status;
                        this.message = response.data.message;
                    } else {
                        window.location.replace(response.data.next);
                    }
                }
            } catch (error) {
                console.log(error.response.data);
            }
            this.loading = true;
        },
    },
    props: {
        native_route: String,
    },
};
</script>
