<template>
    <div
        @mouseover="this.visible = true"
        @mouseleave="this.visible = false"
        class="w-full p-2"
    >
        <div
            :class="`w-full flex flex-col items-${
                this.isSender ? 'end' : 'start'
            }`"
        >
            <div class="flex items-center gap-3 mb-2">
                <!-- <div
                    :class="`
                        order-${this.sender ? '2' : '1'} hidden xl:block
                        grow-0
                        shrink-0
                        w-12
                        h-12
                        rounded-full
                        bg-blue-500
                        text-center
                        py-3`"
                >
                    AV
                </div> -->
                <span
                    @mouseover="this.showFullMessage"
                    @mouseout="this.showLessMessage"
                    :class="`
                        inline-block
                        message
                        grow-1
                        shrink-1
                        order-${this.isSender ? '1' : '2'}
                        inline-block
                        text-${this.isSender ? 'white' : 'black'}
                        bg-${this.isSender ? 'blue-600' : 'gray-300'}
                        rounded-xl
                        max-w-sm
                        md:max-w-md
                        break-word
                        truncate
                        p-2`"
                >
                    {{ this.messagePackage.message }}
                </span>
            </div>
            <div class="flex gap-3 items-center">
                <span :class="`order-${this.isSender ? '3' : '1'}`">
                    {{
                        this.isSender == true
                            ? this.chatUser.username
                            : this.messagePackage.sender.username
                    }}
                </span>
                <i class="text-gray-500 order-2">{{ this.diffForHumans }} </i>
                <button
                    type="button"
                    v-if="this.visible"
                    :class="`order-${this.isSender ? '1' : '3'}`"
                >
                    ...
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import { mapActions, mapMutations, mapGetters } from "vuex";
import { format, register } from "timeago.js";
export default {
    name: "Message",
    data() {
        return {
            visible: false,
            diffForHumans: "",
        };
    },
    props: {
        messagePackage: {
            type: Object,
        },
        isSender: {
            type: Boolean,
            default: true,
        },
    },
    watch: {},
    computed: {
        ...mapGetters(["chatUser"]),
    },
    methods: {
        showFullMessage(e) {
            $(e.target).removeClass("truncate");
            $(e.target).addClass("overflow-auto");
        },
        showLessMessage(e) {
            $(e.target).addClass("truncate");
            $(e.target).removeClass("overflow-auto");
        },
    },
    emits: ["toBottom"],
    mounted() {
        const date = new Date(this.messagePackage.created_at);
        const localeFnc = function (number, index, totalSec) {
            return [
                ["vừa xong", "một lúc"],
                ["%s giây trước", "trong %s giây"],
                ["1 phút trước", "trong 1 phút"],
                ["%s phút trước", "trong %s phút"],
                ["1 giờ trước", "trong 1 giờ"],
                ["%s giờ trước", "trong %s giờ"],
                ["1 ngày trước", "trong 1 ngày"],
                ["%s ngày trước", "trong %s ngày"],
                ["1 tuần trước", "trong 1 tuần"],
                ["%s tuần trước", "trong %s tuần"],
                ["1 tháng trước", "trong 1 tháng"],
                ["%s tháng trước", "trong %s tháng"],
                ["1 năm trước", "trong 1 năm"],
                ["%s năm trước", "trong %s năm"],
            ][index];
        };
        register("vi", localeFnc);
        this.diffForHumans = format(date, "vi");
    },
};
</script>
