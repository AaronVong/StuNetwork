<template>
    <div>
        <div class="mb-2">
            <h5 class="text-lg capitalize">
                {{ this.profile.fullname }}
            </h5>
            <h6 class="text-normal text-gray-600">
                {{ this.username }}
            </h6>
            <div class="text-gray-600 flex flex-wrap gap-2">
                <span>Toast đã đăng: {{ this.toastcount }}</span>
                <span>Đã theo dõi: {{ this.followingcount }}</span>
                <span>Lượt theo dõi: {{ this.followedcount }}</span>
            </div>
            <p class="text-gray-600">
                <i class="fas fa-venus-mars mr-2"></i
                >{{
                    this.profile.gender == null
                        ? "Chưa cập nhật"
                        : this.profile.gender == 1
                        ? "Nam"
                        : "Nữ"
                }}
            </p>
        </div>
        <div
            class="
                flex flex-col
                md:flex-row md:items-center md:flex-wrap
                gap-2
                text-gray-600
            "
        >
            <div clas="flex items-center">
                <span class="mr-2"><i class="fas fa-map-marker-alt"></i></span>
                <span class="capitalize">{{
                    this.profile.address == null
                        ? "Chưa cập nhật"
                        : this.profile.address
                }}</span>
            </div>
            <div class="flex items-center">
                <span class="mr-2"><i class="fas fa-birthday-cake"></i></span>
                <span>{{
                    this.profile.birthday == null
                        ? "Chưa cập nhật"
                        : this.profile.birthday
                }}</span>
            </div>
            <div class="flex items-center">
                <span class="mr-2"><i class="far fa-address-card"></i></span>
                <span class="capitalize">
                    {{ this.rolename ? this.rolename : "Không xác định" }}
                </span>
            </div>
        </div>
    </div>
</template>
<script>
import { mapGetters } from "vuex";
export default {
    name: "ProfileInfo",
    props: {
        rolename: String,
        username: String,
        followingcount: Number,
        followedcount: Number,
        toastcount: Number,
    },
    computed: {
        ...mapGetters(["profile"]),
    },
};
</script>
