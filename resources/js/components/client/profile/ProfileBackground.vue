<template>
    <div class="w-full h-full" :style="styleObject"></div>
</template>
<script>
export default {
    data() {
        return {
            styleObject: {
                backgroundImage:
                    this.url != null
                        ? `url('${this.url}')`
                        : "url('https://via.placeholder.com/250')",
                backgroundRepeat: "no-repeat",
                backgroundPosition: "center",
                backgroundSize: "cover",
            },
        };
    },
    name: "ProfileBackground",
    props: {
        url: {
            type: String,
        },
    },
};
</script>
