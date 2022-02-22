<template>
    <div>
        <form @submit="this.handleSubmit" class="w-full h-full">
            <div class="flex flex-col justify-center mb-3">
                <label for="old_password" class="mb-3 text-xl font-medium">
                    Mật khẩu cũ:
                </label>
                <input
                    v-model="this.form.old_password"
                    type="text"
                    name="old_password"
                    id="old_password"
                    class="
                        w-full
                        p-3
                        h-12
                        rounded-lg
                        focus:outline-none
                        focus:ring
                        focus-ring-1
                        focus:ring-blue-400
                        border
                    "
                />
                <span
                    v-if="this.validates.old_password"
                    class="text-red-500 py-2"
                >
                    {{ this.validates.old_password.shift() }}
                </span>
            </div>
            <div class="flex flex-col justify-center mb-3">
                <label for="password" class="mb-3 text-xl font-medium">
                    Mật khẩu mới:
                </label>
                <input
                    v-model="this.form.password"
                    type="password"
                    name="password"
                    id="password"
                    class="
                        w-full
                        p-3
                        h-12
                        rounded-lg
                        focus:outline-none
                        focus:ring
                        focus-ring-1
                        focus:ring-blue-400
                        border
                    "
                />
                <span v-if="this.validates.password" class="text-red-500 py-2">
                    {{ this.validates.password.shift() }}
                </span>
            </div>
            <div class="flex flex-col justify-center mb-3">
                <label
                    for="password_confirmation "
                    class="mb-3 text-xl font-medium"
                >
                    Nhập lại mật khẩu:
                </label>
                <input
                    v-model="this.form.password_confirmation"
                    type="password"
                    name="password_confirmation "
                    id="password_confirmation "
                    class="
                        w-full
                        p-3
                        h-12
                        rounded-lg
                        focus:outline-none
                        focus:ring
                        focus-ring-1
                        focus:ring-blue-400
                        border
                    "
                />
                <span
                    v-if="this.validates.password_confirmation"
                    class="text-red-500 py-2"
                >
                    {{ this.validates.password_confirmation.shift() }}
                </span>
            </div>
            <button
                type="submit"
                :class="`
                        ${this.empty ? 'btn-isDisabled' : ''}
                        w-full
                        rounded-full
                        w-32
                        py-3
                        bg-blue-500
                        hover:bg-blue-600
                        focus:outline-none
                        focus:ring
                        focus:ring-1
                        focus:ring-blue-400
                        text-gray-200 text-lg
                    `"
                :disabled="this.empty"
            >
                Xác nhận
            </button>
        </form>
    </div>
</template>

<script>
import axios from "axios";
export default {
    name: "ChangePassword",
    data() {
        return {
            form: {
                old_password: "",
                password: "",
                password_confirmation: "",
            },
            validates: {},
            errorMessage: "",
        };
    },
    props: {
        visible: Boolean,
    },
    watch: {
        visible: {
            handler: function (oldVal, newVal) {
                if (newVal == false) {
                    this.form.old_password =
                        this.form.password =
                        this.form.password_confirmation =
                            "";
                }
            },
            immediate: true,
        },
    },
    computed: {
        empty() {
            return (
                !this.form.old_password &&
                !this.form.password &&
                !this.form.password_confirmation
            );
        },
    },
    methods: {
        async handleSubmit(e) {
            e.preventDefault();
            const loading = this.$loading({
                fullscreen: true,
                text: "Đang xử lý, vui lòng chờ...",
            });
            this.validates = {};
            const response = await axios
                .post("/user/change-password", {
                    _method: "PUT",
                    ...this.form,
                })
                .catch((error) => {
                    if (error.response.status == 422) {
                        this.validates = { ...error.response.data.validates };
                    } else {
                        this.errorMessage = error.response.data.message;
                    }
                    this.form.password = this.form.password_confirmation = "";
                });
            if (response) {
                this.$message({
                    type: "success",
                    message: response.data.message,
                });
                this.form.old_password =
                    this.form.password =
                    this.form.password_confirmation =
                        "";
            }
            loading.close();
        },
    },
    mounted() {},
};
</script>
