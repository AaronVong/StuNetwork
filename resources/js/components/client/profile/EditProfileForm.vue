<template>
    <div class="w-full flex">
        <button
            class="
                ml-auto
                mt-2
                p-2
                bg-white
                rounded-full
                border border-blue-400
                text-blue-400
                font-medium
                hover:bg-blue-100
                transition-colors
                duration-200
                focus:outline-none
            "
            v-on:click="this.toggleEditProfile"
        >
            Chỉnh sửa Profile
        </button>
    </div>
    <el-dialog
        v-model="this.editProfileVisible"
        width="80%"
        @closed="
            () => {
                this.form = { ...this.profile };
            }
        "
    >
        <h3 class="text-center text-2xl py-2">Cập nhật Profile</h3>
        <p
            class="text-center text-red-500 mb-3"
            v-if="this.profileErrorMessage"
        >
            {{ this.profileErrorMessage }}
        </p>
        <form method="post" @submit="this.handleSubmit">
            <div class="flex flex-col justify-center mb-3">
                <label for="fullname" class="mb-3 text-xl font-medium"
                    >Họ tên:</label
                >
                <input
                    v-model="this.form.fullname"
                    type="text"
                    name="fullname"
                    id="fullname"
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
                    class="text-red-500"
                    v-if="this.profileValidates.fullname"
                >
                    {{ this.profileValidates.fullname.shift() }}
                </span>
            </div>
            <div class="flex flex-col justify-center mb-3">
                <label for="phone" class="mb-3 text-xl font-medium"
                    >Số điện thoại:</label
                >
                <input
                    v-model="this.form.phone"
                    type="text"
                    name="phone"
                    id="phone"
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
                <span class="text-red-500" v-if="this.profileValidates.phone">
                    {{ this.profileValidates.phone.shift() }}
                </span>
            </div>
            <div class="flex flex-col justify-center mb-3">
                <label for="address" class="mb-3 text-xl font-medium"
                    >Địa chỉ:</label
                >
                <input
                    v-model="this.form.address"
                    type="text"
                    name="address"
                    id="address"
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
                <span class="text-red-500" v-if="this.profileValidates.address">
                    {{ this.profileValidates.address.shift() }}
                </span>
            </div>
            <div class="flex flex-col justify-center mb-3">
                <label for="birthday" class="mb-3 text-xl font-medium"
                    >Ngày sinh:</label
                >
                <input
                    v-model="this.form.birthday"
                    type="date"
                    name="birthday"
                    id="birthday"
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
                    class="text-red-500"
                    v-if="this.profileValidates.birthday"
                >
                    {{ this.profileValidates.birthday.shift() }}
                </span>
            </div>
            <div class="flex flex-col justify-center mb-3">
                <label class="mr-2 text-xl font-medium">Giới tính:</label>
                <div class="flex items-center">
                    <div class="flex items-center mr-2">
                        <label for="male" class="mr-2 text-lg">Nam:</label>
                        <input
                            v-model="this.form.gender"
                            type="radio"
                            name="gender"
                            id="male"
                            value="1"
                        />
                    </div>
                    <div class="flex items-center mr-2">
                        <label for="female" class="mr-2 text-lg">Nữ:</label>
                        <input
                            v-model="this.form.gender"
                            type="radio"
                            name="gender"
                            id="female"
                            value="0"
                        />
                    </div>
                </div>
                <span class="text-red-500" v-if="this.profileValidates.gender">
                    {{ this.profileValidates.gender.shift() }}
                </span>
            </div>
            <div class="flex gap-4 mb-3">
                <div class="flex flex-col">
                    <label class="mb-2 text-xl font-medium">Ảnh đại diện</label>
                    <el-upload
                        list-type="picture-card"
                        :action="
                            !this.form.avatarUrl ? '' : this.form.avatarUrl
                        "
                        :auto-upload="false"
                        :show-file-list="false"
                        :multiple="false"
                        :on-change="this.handleAvatarChange"
                        :accept="this.extensions.join(',')"
                    >
                        <img
                            v-if="this.form.avatarUrl"
                            :src="this.form.avatarUrl"
                            class="w-full h-full"
                        />
                        <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                    </el-upload>
                    <button
                        type="button"
                        class="p-2 bg-red-500 text-white"
                        @click="this.clearAvatar"
                    >
                        Hủy thay đổi
                    </button>
                </div>
                <div class="flex flex-col">
                    <label class="mb-2 text-xl font-medium">Ảnh nền</label>
                    <el-upload
                        list-type="picture-card"
                        :action="
                            !this.form.backgroundUrl
                                ? ''
                                : this.form.backgroundUrl
                        "
                        :auto-upload="false"
                        :show-file-list="false"
                        :multiple="false"
                        :on-change="this.handleBackgroundChange"
                        :accept="this.extensions.join(',')"
                    >
                        <img
                            v-if="this.form.backgroundUrl"
                            :src="this.form.backgroundUrl"
                            class="w-full h-full"
                        />
                        <i v-else class="el-icon-plus"></i>
                    </el-upload>
                    <button
                        type="button"
                        class="p-2 bg-red-500 text-white"
                        @click="this.clearBackground"
                    >
                        Hủy thay đổi
                    </button>
                </div>
            </div>
            <div class="flex">
                <button
                    type="submit"
                    class="
                        w-full
                        rounded-full
                        w-32
                        py-3
                        bg-blue-500
                        hover:bg-blue-600
                        focus:outline-none
                        focus:ring focus:ring-1 focus:ring-blue-400
                        text-gray-200 text-lg
                    "
                >
                    Lưu thay đổi
                </button>
            </div>
        </form>
        <template #footer>
            <span class="dialog-footer">
                <el-button @click="this.toggleEditProfile">Hủy bỏ</el-button>
            </span>
        </template>
    </el-dialog>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
export default {
    name: "EditProfileForm",
    data() {
        return {
            form: {
                fullname: "",
                address: "",
                phone: "",
                gender: true,
                birthday: "",
                avatarUrl: "",
                backgroundUrl: "",
            },
            avartar: null,
            background: null,
            editProfileVisible: false,
            extensions: [".jpg", ".jpeg", ".png"],
        };
    },
    props: {
        username: {
            type: String,
            default: "",
        },
    },
    methods: {
        ...mapActions(["editProfileAction"]),
        isValidFile(file) {
            if (!file) return true;
            const filename = file.name;
            const fileExtension = filename.slice(
                ((filename.lastIndexOf(".") - 1) >>> 0) + 2
            );
            return this.extensions.some((item) =>
                item.indexOf(fileExtension) != -1 ? true : false
            );
        },
        async handleSubmit(e) {
            e.preventDefault();
            if (
                !this.isValidFile(this.avartar) ||
                !this.isValidFile(this.background)
            ) {
                this.$message.warning("Chỉ hộ trợ file hình ảnh!");
                return;
            }
            const loading = this.$loading({
                fullscreen: true,
                text: "Đang xử lý, vui lòng chờ...",
            });
            let formData = new FormData();
            formData.set("fullname", this.form.fullname);
            formData.set("phone", this.form.phone);
            formData.set("address", this.form.address);
            formData.set("birthday", this.form.birthday);
            formData.set("gender", this.form.gender);
            if (this.avartar != null) {
                formData.set("avatar", this.avartar.raw, this.avartar.name);
            }
            if (this.background != null) {
                formData.set(
                    "background",
                    this.background.raw,
                    this.background.name
                );
            }
            formData.set("_method", "put");
            const ok = await this.editProfileAction({
                username: this.username,
                formData,
            });
            if (ok) {
                this.toggleEditProfile();
                this.$message.success({
                    message: this.profileInfoMessage,
                });
            }
            loading.close();
        },
        toggleEditProfile() {
            this.editProfileVisible = !this.editProfileVisible;
            this.form = { ...this.profile };
        },
        handleAvatarChange(file, fileList) {
            this.form.avatarUrl = file.url;
            this.avartar = file;
        },
        handleBackgroundChange(file, fileList) {
            this.form.backgroundUrl = file.url;
            this.background = file;
        },
        clearAvatar() {
            this.form.avatarUrl = !this.profile.avatarUrl
                ? ""
                : this.profile.avatarUrl;
            this.avartar = null;
        },
        clearBackground() {
            this.form.backgroundUrl = !this.profile.backgroundUrl
                ? ""
                : this.profile.backgroundUrl;
            this.background = null;
        },
    },
    computed: {
        ...mapGetters([
            "profile",
            "profileValidates",
            "profileErrorMessage",
            "profileInfoMessage",
        ]),
    },
    mounted() {
        this.form = { ...this.profile };
    },
};
</script>
