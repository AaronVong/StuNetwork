<template>
    <ul class="w-full h-full py-3">
        <Comment
            v-for="(comment, index) in this.commentList"
            :comment="comment"
            :key="index"
            :owned="this.owner ? this.owner == comment.commenter_id : false"
            :owner="this.owner"
        />
    </ul>
</template>

<script>
import Comment from "./Comment.vue";
import { mapMutations, mapGetters } from "vuex";
export default {
    name: "Comments",
    props: {
        comments: Array,
        replies: {
            type: Array,
            default: null,
        },
        owner: {
            type: Number,
            default: null,
        },
    },
    computed: {
        ...mapGetters(["commentList"]),
    },
    methods: {
        ...mapMutations(["setCommentList", "setReplyList"]),
    },
    components: { Comment },
    mounted() {
        this.setCommentList(this.comments);
        if (this.replies) {
            this.setReplyList(this.replies);
        }
    },
};
</script>
