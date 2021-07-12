<template>
    <div
        class="
            w-full
            h-full
            flex flex-col
            items-center
            gap-6
            px-6
            py-3
            lg:flex-row
        "
    >
        <div class="w-32 h-32">
            <img
                v-if="this.globalProfile.avatarUrl"
                :src="this.globalProfile.avatarUrl"
                class="block"
            />
            <div
                v-else
                class="
                    flex
                    items-center
                    justify-center
                    bg-green-700
                    text-white
                    w-32
                    h-32
                    text-2xl
                "
            >
                {{ this.globalProfile.fullname.split(" ").pop() }}
            </div>
        </div>
        <div class="max-w-full">
            <ul class="list-none w-full">
                <li class="flex items-center gap-3 mb-3">
                    <span class="font-bold">Họ tên:</span>
                    <span class="capitalize">
                        {{ this.globalProfile.fullname }}
                    </span>
                </li>
                <li class="flex gap-3 mb-3">
                    <span class="font-bold">Giới tính:</span>
                    <span
                        :class="`capitalize ${
                            this.globalProfile.gender == null
                                ? 'text-gray-300 '
                                : ''
                        }`"
                    >
                        {{
                            this.globalProfile.gender == null
                                ? "Chưa cập nhật"
                                : this.globalProfile.gender == 1
                                ? "Nam"
                                : "Nữ"
                        }}
                    </span>
                </li>
                <li class="flex gap-3 mb-3">
                    <span class="font-bold">Email:</span>
                    <span class="underline">
                        {{ this.user.email }}
                    </span>
                </li>
                <li class="flex items-center gap-3 mb-3">
                    <span class="font-bold">Roles:</span>
                    <ul
                        class="
                            w-3/5
                            list-none
                            flex
                            items-center
                            flex-wrap
                            gap-3
                            text-gray-900
                        "
                    >
                        <li
                            v-for="(role, index) in this.user.roles"
                            :key="index"
                            class="
                                bg-yellow-500
                                rounded-full
                                py-2
                                px-4
                                transition-colors
                                font-semibold
                                cursor-pointer
                                hover:bg-yellow-600
                            "
                        >
                            {{ role.name }}
                        </li>
                    </ul>
                </li>
                <li class="flex flex-col gap-3 mb-3">
                    <span class="font-bold">Permissions:</span>
                    <ul
                        class="
                            w-full
                            list-none
                            grid grid-cols-2 grid-rows-auto
                            gap-6
                            text-gray-300 text-lg
                        "
                    >
                        <li
                            v-for="(permission, index) in this.user_permissions"
                            :key="index"
                            class="flex items-center"
                        >
                            <label
                                :for="permission.name"
                                class="w-32 px-2 cursor-pointer"
                                >{{ permission.name }}:</label
                            >
                            <input
                                @change="
                                    this.handleCheckboxChange(
                                        $event,
                                        permission.name
                                    )
                                "
                                :id="permission.name"
                                :checked="
                                    this.permissions.some(
                                        (item) => item.name == permission.name
                                    )
                                "
                                :value="permission.name"
                                :name="permission.name"
                                type="checkbox"
                                class="cursor-pointer"
                            />
                        </li>
                    </ul>
                </li>
            </ul>
            <form @submit="this.handleSubmit">
                <button
                    :disabled="this.disabled"
                    type="submit"
                    :class="`rounded-full
                        px-4
                        py-2
                        bg-green-500
                        hover:bg-green-600
                        focus:outline-none ${
                            this.disabled ? 'btn-isDisabled ' : ''
                        }`"
                >
                    Lưu thay đổi
                </button>
            </form>
        </div>
    </div>
</template>

<script>
import { mapActions, mapGetters, mapMutations } from "vuex";
export default {
    name: "UserDetail",
    data() {
        return {
            form: {
                fullname: "",
            },
            edit: {
                fullname: false,
            },
            profileUpdated: false,
            permissionChanged: {},
        };
    },
    computed: {
        ...mapGetters({
            globalProfile: "profile",
        }),
        disabled() {
            return Object.keys(this.permissionChanged).length <= 0;
        },
    },
    props: {
        user: {
            type: Object,
            default: {},
        },
        user_permissions: Array,
        profile: Object,
        permissions: Array,
    },
    methods: {
        ...mapMutations(["setProfile"]),
        ...mapActions(["editUserAccount"]),
        handleCheckboxChange(e) {
            const { name, checked } = e.target;
            this.permissionChanged[name] = {
                name,
                checked,
            };
        },
        async handleSubmit(e) {
            e.preventDefault();
            await this.editUserAccount({
                permissions: this.permissionChanged,
                username: this.user.username,
            });
        },
    },
    beforeMount() {
        this.setProfile(this.profile);
    },
};
</script>
