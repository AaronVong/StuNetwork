<template>
    <form class="w-full" @submit="this.handleSubmit">
        <ul class="list-none w-full flex flex-col gap-3 text-xl">
            <li v-for="(setting, index) in this.settings" :key="index">
                <div class="flex items-center gap-3">
                    <label :for="setting.name" class="w-2/5">
                        {{ setting.name }}
                    </label>
                    <input
                        :id="setting.name"
                        type="checkbox"
                        :checked="setting.pivot.value"
                        v-model="setting.pivot.value"
                    />
                </div>
            </li>
        </ul>
        <button
            type="submit"
            class="
                text-white
                px-4
                py-2
                bg-blue-400
                border-xl
                hover:bg-blue-500
                focus:outline-none
            "
        >
            Lưu thay đổi
        </button>
    </form>
</template>
<script>
import axios from "axios";
import { mapActions, mapGetters, mapMutations } from "vuex";
export default {
    name: "ChatSettings",
    data() {
        return {
            settings: [],
        };
    },
    props: {
        username: String,
        isOpen: Boolean,
    },
    watch: {
        isOpen: {
            handler: function (oldVal, newVal) {
                if (newVal == false) {
                    // dialog đã đóng, reset giá trị
                    this.settings = JSON.parse(
                        JSON.stringify(this.userSettings)
                    );
                }
            },
            immediate: true,
        },
    },
    computed: {
        ...mapGetters(["userSettings"]),
    },
    methods: {
        ...mapActions(["getUserSettings"]),
        ...mapMutations(["setUserSettings"]),
        async handleSubmit(e) {
            e.preventDefault();
            const loading = this.$loading({
                fullscreen: true,
            });
            const response = await axios
                .post("/settings", { newSettings: this.settings })
                .catch((error) => console.log(error));
            if (response) {
                this.setUserSettings(response.data);
                this.settings = JSON.parse(
                    JSON.stringify(response.data.settings)
                );
            }
            loading.close();
        },
    },
    components: {},
    async mounted() {
        await this.getUserSettings(this.username);
        // deep cloning
        this.settings = JSON.parse(JSON.stringify(this.userSettings));
    },
};
</script>
