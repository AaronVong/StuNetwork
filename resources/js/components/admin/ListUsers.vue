<template>
    <div class="w-full h-96 p-3 overflow-y-auto">
        <table
            class="
                relative
                max-h-96
                w-full
                table-auto
                border-2 border-gray-300 border-collapse
                bg-white
                text-gray-900
            "
        >
            <thead>
                <tr class="bg-gray-200 text-lg">
                    <th class="border-2 border-gray-300 border-collapse p-3">
                        Tên tài khoản
                    </th>
                    <th class="border-2 border-gray-300 border-collapse p-3">
                        Họ tên
                    </th>
                    <th class="border-2 border-gray-300 border-collapse p-3">
                        Địa chỉ email
                    </th>
                    <th class="border-2 border-gray-300 border-collapse p-3">
                        Giới tính
                    </th>
                    <th
                        class="border-2 border-gray-300 border-collapse p-3"
                    ></th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="(user, index) in this.localUsers"
                    :key="index"
                    class="hover:bg-gray-100 transition-colors"
                >
                    <td class="border-2 border-gray-300 border-collapse p-3">
                        {{ user.username }}
                    </td>
                    <td class="border-2 border-gray-300 border-collapse p-3">
                        {{ user.profile.fullname }}
                    </td>
                    <td
                        class="
                            border-2 border-gray-300 border-collapse
                            p-3
                            underline
                        "
                    >
                        {{ user.email }}
                    </td>
                    <td class="border-2 border-gray-300 border-collapse p-3">
                        {{ user.profile.gender }}
                    </td>
                    <td class="border-2 border-gray-300 border-collapse p-3">
                        <div
                            class="w-full flex items-center justify-start gap-3"
                        >
                            <a
                                :href="`/list-users/${user.username}`"
                                class="
                                    rounded-xl
                                    px-4
                                    py-2
                                    bg-blue-500
                                    text-gray-100
                                    hover:bg-blue-600
                                "
                            >
                                Chi tiết
                            </a>
                            <form
                                @submit="
                                    this.handleSubmit($event, user.username)
                                "
                                class="inline-block"
                            >
                                <button
                                    v-loading="this.revokePermissionProcess"
                                    type="submit"
                                    class="
                                        rounded-xl
                                        px-4
                                        py-2
                                        bg-red-500
                                        text-gray-100
                                        hover:bg-red-600
                                    "
                                >
                                    {{
                                        user.permissions.some(
                                            (permission) =>
                                                permission.name == "login"
                                        )
                                            ? "Chặn đăng nhập"
                                            : "Mở đăng nhập"
                                    }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
export default {
    name: "MemberList",
    data() {
        return {
            page: 1,
            localUsers: [],
            revokePermissionProcess: false,
        };
    },
    props: {
        users: Object,
    },
    computed: {
        ...mapGetters(["adminErrorMessage"]),
    },
    methods: {
        ...mapActions(["loginPermission"]),
        handleClick(row, column, cell, event) {
            console.log(column);
        },
        async handleSubmit(e, username) {
            e.preventDefault();
            this.revokePermissionProcess = true;
            const revoke = await this.loginPermission(username);
            const $element = $(e.target);
            if (revoke) {
                // nếu thành công (true && not null) -> thu hồi quyền đăng nhập thành công
                $element.children("button").text("Mở đăng nhập");
            } else if (revoke == false) {
                $element.children("button").text("Chặn đăng nhập");
            } else {
                this.$message({
                    type: "error",
                    message: this.adminErrorMessage,
                });
            }
            this.revokePermissionProcess = false;
        },
    },
    mounted() {
        this.localUsers = [...this.users.data];
        this.page++;
    },
};
</script>
