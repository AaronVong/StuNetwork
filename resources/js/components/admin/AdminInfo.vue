<template>
    <EditProfileForm
        :username="this.admin.username"
        buttonClass="mx-auto mb-3 py-2 px-3 bg-gray-100 rounded-full text-black font-medium hover:bg-gray-300 transition-colors focus:outline-none"
    />
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
                        {{ this.admin.email }}
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
                            v-for="(role, index) in this.admin.roles"
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
                            v-for="(permission, index) in this.permissions"
                            :key="index"
                            class="
                                bg-gray-200
                                rounded-full
                                py-2
                                px-4
                                transition-colors
                                font-semibold
                                cursor-pointer
                                hover:bg-gray-300
                            "
                        >
                            {{ permission.name }}
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
import EditProfileForm from "../client/profile/EditProfileForm.vue";
import { mapGetters, mapMutations } from "vuex";
export default {
    name: "AdminInfo",
    data() {
        return {
            form: {
                fullname: "",
            },
            edit: {
                fullname: false,
            },
            profileUpdated: false,
        };
    },
    computed: {
        ...mapGetters({
            globalProfile: "profile",
        }),
    },
    components: { EditProfileForm },
    props: {
        admin: {
            type: Object,
            default: {},
        },
        profile: Object,
        permissions: Array,
    },
    methods: {
        ...mapMutations(["setProfile"]),
    },
    beforeMount() {
        this.setProfile(this.profile);
    },
};
</script>
