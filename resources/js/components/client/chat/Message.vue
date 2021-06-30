<template>
    <div
        @mouseover="this.visible = true"
        @mouseleave="this.visible = false"
        class="w-full p-2"
    >
        <div
            :class="`w-full flex flex-col items-${
                this.sender ? 'end' : 'start'
            }`"
        >
            <div class="flex items-center gap-3 mb-2">
                <div
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
                </div>
                <span
                    :class="`
                        grow-1
                        shrink-1
                        order-${this.sender ? '1' : '2'}
                        inline-block
                        text-${this.sender ? 'white' : 'black'}
                        bg-${this.sender ? 'blue-600' : 'gray-300'}
                        rounded-xl
                        max-w-sm
                        md:max-w-md
                        break-word
                        p-2`"
                >
                    {{ this.message }}
                </span>
            </div>
            <div class="xl:ml-16 flex gap-3 items-center">
                <span :class="`order-${this.sender ? '3' : '1'}`"
                    >user name</span
                >
                <i class="text-gray-500 order-2">1 minutes ago</i>
                <button
                    type="button"
                    v-if="this.visible"
                    :class="`order-${this.sender ? '1' : '3'}`"
                >
                    ...
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import {} from "vuex";
export default {
    name: "Message",
    data() {
        return {
            visible: false,
        };
    },
    props: {
        messagePackage: {
            type: Object,
        },
        sender: {
            type: Boolean,
            default: true,
        },
    },
    watch: {},
    computed: {},
    methods: {},
    mounted() {},
};
</script>
