<template>
    <div class="w-full h-full flex flex-col">
        <div class="w-full h-full border">
            <!-- Messages Display-->
        </div>
        <div class="w-full h-20 border mt-auto">
            <form
                @submit="this.handleSendMessage"
                class="w-full h-full flex items-center"
            >
                <div class="w-full h-full flex items-center">
                    <input
                        v-model="this.form.message"
                        type="text"
                        placeholder="Tin nhắn..."
                        class="w-full px-2 py-3 focus:outline-none"
                    />
                </div>
                <div class="flex items-center justify-center w-20 h-full">
                    <button
                        type="submit"
                        :class="`                            flex
                            items-center
                            justify-center
                            h-1/2
                            w-1/2
                            rounded-full
                            bg-blue-500
                            text-gray-100
                            
                            ${
                                this.form.message
                                    ? 'hover:text-white'
                                    : 'btn-isDisabled'
                            }`"
                    >
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    name: "Chat",
    data() {
        return {
            form: {
                message: "",
                files: [],
            },
        };
    },
    computed: {},
    components: {},
    methods: {
        handleSendMessage(e) {
            e.preventDefault();
            if (!this.form.message) return;
            alert(this.form.message);
        },
    },
};
</script>
