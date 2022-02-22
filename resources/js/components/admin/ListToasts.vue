<template>
    <div
        v-infinite-scroll="load"
        infinite-scroll-immediate="false"
        infinite-scroll-disabled="disabledInfiniteScroll"
        infinite-scroll-distance="10"
        class="w-full h-96 p-3 overflow-y-auto"
    >
        <table
            v-loading="this.sorting"
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
                        Toast ID
                        <button
                            v-if="this.asc"
                            @click="this.sort($event, 'id')"
                            class="text-blue-500"
                        >
                            Desc
                        </button>
                        <button
                            v-else
                            @click="this.sort($event, 'id')"
                            class="text-red-500"
                        >
                            Asc
                        </button>
                    </th>
                    <th class="border-2 border-gray-300 border-collapse p-3">
                        Người đăng
                    </th>
                    <th class="border-2 border-gray-300 border-collapse p-3">
                        Nội dung
                    </th>
                    <th class="border-2 border-gray-300 border-collapse p-3">
                        Hình ảnh
                    </th>
                    <th class="border-2 border-gray-300 border-collapse p-3">
                        Ngày đăng
                    </th>
                    <th class="border-2 border-gray-300 border-collapse p-3">
                        Cập nhật lần cuối
                    </th>
                    <th
                        class="border-2 border-gray-300 border-collapse p-3"
                    ></th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="(toast, index) in this.localToasts"
                    :key="index"
                    class="hover:bg-gray-100 transition-colors"
                >
                    <td
                        class="
                            border-2 border-gray-300 border-collapse
                            p-3
                            text-center
                        "
                    >
                        {{ toast.id }}
                    </td>
                    <td class="border-2 border-gray-300 border-collapse p-3">
                        <a :href="`/dashboard/toast/${toast.user.username}`">
                            {{ toast.user.username }}
                        </a>
                    </td>
                    <td class="border-2 border-gray-300 border-collapse p-3">
                        <p class="w-36 truncate">
                            {{ toast.content }}
                        </p>
                    </td>
                    <td
                        class="
                            border-2 border-gray-300 border-collapse
                            p-3
                            h-auto
                        "
                    >
                        <div
                            v-for="(file, index) in toast.files"
                            :key="index"
                            :class="`w-24 h-24 flex flex-wrap gap-2 items-center min-h-full`"
                        >
                            <!-- Toast files place here -->
                            <img
                                class="max-w-full max-h-full"
                                :src="file.url"
                                :alt="file.name"
                            />
                        </div>
                    </td>
                    <td class="border-2 border-gray-300 border-collapse p-3">
                        {{ toast.created_at }}
                    </td>
                    <td class="border-2 border-gray-300 border-collapse p-3">
                        {{ toast.updated_at }}
                    </td>
                    <td class="border-2 border-gray-300 border-collapse p-3">
                        <form
                            @submit="this.deleteToast($event, toast.id)"
                            class="inline-block"
                        >
                            <button
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
                                Xóa
                            </button>
                        </form>
                    </td>
                </tr>
                <tr v-if="this.loading">
                    <td
                        colspan="7"
                        class="
                            text-center
                            border-2 border-gray-300 border-collapse
                            p-3
                        "
                    >
                        Đang tải...
                    </td>
                </tr>
                <tr v-if="this.noMore">
                    <td
                        colspan="7"
                        class="
                            text-center
                            border-2 border-gray-300 border-collapse
                            p-3
                        "
                    >
                        không còn dữ liệu
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import { quickSort } from "../../functions";
import ToastFile from "../client/toast/ToastFile.vue";
import axios from "axios";
export default {
    name: "ListToasts",
    data() {
        return {
            page: 1,
            localToasts: [],
            loading: false,
            noMore: false,
            asc: false,
            sorting: false,
        };
    },
    components: { ToastFile },
    props: { toasts: Object },
    computed: {
        ...mapGetters(["adminErrorMessage"]),
        disabledInfiniteScroll() {
            return this.loading || this.noMore;
        },
    },
    methods: {
        ...mapActions(["loginPermission", "deleteToastAction"]),
        async deleteToast(event, toastID) {
            event.preventDefault();
            if (!toastID) {
                return;
            }
            const ok = await this.deleteToastAction(toastID);
            if (ok) {
                alert("Toast đã được xóa");
                this.localToasts = this.localToasts.filter(
                    (toast) => toast.id != toastID
                );
            } else {
                alert("Xảy ra lỗi, xem console");
            }
        },
        async load() {
            this.loading = true;
            try {
                const response = await axios.get(
                    `/list-toasts?page=${this.page}`
                );
                if (response.status == 200) {
                    this.page++;
                    this.localToasts.push(...response.data.toasts.data);
                } else if (response.status == 204) {
                    this.noMore = true;
                }
            } catch (error) {
                console.log(error);
            }
            this.loading = false;
        },
        async sort(e, field) {
            e.preventDefault();
            this.asc = !this.asc;
            this.sorting = true;
            this.localToasts = await quickSort(
                [...this.localToasts],
                0,
                this.localToasts.length - 1,
                this.asc,
                field
            );
            this.sorting = false;
        },
    },
    beforeMount() {
        this.localToasts = [...this.toasts.data];
        this.page++;
    },
    mounted() {
        const regex = new RegExp("^(/dashboard/toast)/[a-zA-Z]+$", "i");
        const result = regex.test(window.location.pathname);
        if (result) {
            this.noMore = true;
            this.loading = false;
        }
    },
};
</script>
