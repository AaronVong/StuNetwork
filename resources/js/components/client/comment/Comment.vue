<template>
    <div class="ml-3 comment px-2 border-l border-black">
        <div class="w-full flex mb-3">
            <div class="flex-grow-0 flex-shrink-0 max-w-xs mr-2">
                <img
                    v-if="comment.commenter.profile.avatarUrl"
                    :src="comment.commenter.profile.avatarUrl"
                    class="block md:w-16 md:h-16 rounded-full h-12 w-12"
                />
                <img
                    v-else
                    src="https://via.placeholder.com/50"
                    class="block md:w-16 md:h-16 rounded-full h-12 w-12"
                />
            </div>
            <div class="flex flex-col">
                <a
                    v-bind:href="`/profile/${comment.commenter.username}`"
                    class="hover:underline"
                >
                    <span class="font-medium">{{
                        comment.commenter.profile.fullname
                    }}</span>
                </a>
                <span class="font-normal text-gray-500 emphasis"
                    ><i>{{ comment.commenter.username }}</i></span
                >
            </div>
        </div>
        <div>
            <p class="p-3">
                {{ comment.comment }}
            </p>
        </div>
        <div>
            <button
                class="
                    modal__btn
                    focus:outline-none
                    text-indigo-600
                    pill-hover pill-hover--cycle
                "
                @click="this.toggleVisible"
                type="button"
            >
                <i class="fas fa-comment"></i>
            </button>
        </div>
        <CommentForm
            :visible="this.visible"
            :parentId="this.comment.id"
            :toast_id="parseInt(this.comment.commentable_id)"
            v-on:closeCommentForm="this.toggleVisible"
        />
        <div>
            <!-- Replies -->
            <Comment
                v-for="(reply, index) in this.replyList.filter(
                    (reply) => reply.child_id == this.comment.id
                )"
                :key="index"
                :comment="reply"
            />
        </div>
    </div>
</template>

<script>
import CommentForm from "./CommentForm.vue";
import { mapGetters } from "vuex";
export default {
    name: "Comment",
    data() {
        return {
            visible: false,
        };
    },
    computed: {
        ...mapGetters(["replyList"]),
    },
    props: {
        comment: Object,
        isComment: {
            type: Boolean,
            default: true,
        },
    },
    methods: {
        toggleVisible() {
            this.visible = !this.visible;
        },
    },
    components: { CommentForm },
};
</script>
