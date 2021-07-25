<template>
    <button
        @click="this.toggleQuickToast"
        type="button"
        class="
            font-medium
            text-gray-700 text-2xl
            rounded-full
            h-12
            w-12
            flex
            items-center
            bg-blue-500
            justify-center
            lg:w-full lg:h-12
            focus:outline-none focus:ring-2
            hover:bg-blue-400
            transition-colors
            duration-200
        "
    >
        <i class="fas fa-feather"></i>
    </button>
    <el-dialog
        title="Quick Toast"
        width="80%"
        v-model="this.quickToastVisible"
        @closed="
            () => {
                if (this.quickToastVisible) this.quickToastVisible = false;
                if (this.discard == false) this.discard = true;
            }
        "
    >
        <div class="w-full">
            <ToastForm
                :native_route="this.native_route"
                :native_method="this.native_method"
                :discard="this.discard"
                v-on:toastCreated="this.toggleQuickToast"
            ></ToastForm>
        </div>
        <template #footer>
            <div class="flex justify-end items-center gap-4">
                <el-button @click="this.toggleQuickToast">Hủy bỏ</el-button>
            </div>
        </template>
    </el-dialog>
</template>
<script>
import ToastForm from "./ToastForm";
export default {
    name: "QuickToast",
    data() {
        return {
            quickToastVisible: false,
            discard: true,
        };
    },
    props: {
        native_route: String,
        native_method: String,
    },
    components: { ToastForm },
    methods: {
        toggleQuickToast() {
            this.quickToastVisible = !this.quickToastVisible;
            this.discard = !this.discard;
        },
    },
};
</script>
