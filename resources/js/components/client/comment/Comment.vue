<template>
    <div class="ml-3 comment border-l-4 border-gray-300 mb-3">
        <div class="hover:bg-gray-100 p-3">
            <div class="w-full flex mb-3">
                <div class="flex-grow-0 flex-shrink-0 max-w-xs mr-2">
                    <img
                        v-if="comment.commenter.profile.avatarUrl"
                        :src="comment.commenter.profile.avatarUrl"
                        class="
                            block
                            md:w-16 md:h-16
                            rounded-full
                            border border-gray-500
                            h-12
                            w-12
                        "
                    />
                    <img
                        v-else
                        src="https://via.placeholder.com/50"
                        class="
                            block
                            md:w-16 md:h-16
                            rounded-full
                            h-12
                            w-12
                            border
                        "
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
                <!-- Tools -->

                <div class="ml-auto">
                    <CommentTools
                        :comment="this.comment"
                        :processable="this.owned"
                    />
                </div>
            </div>
            <!-- Comment Content -->
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
                        text-blue-500
                        pill-hover pill-hover--cycle
                    "
                    @click="this.toggleCommentForm"
                    type="button"
                >
                    <i class="fas fa-comment"></i>
                </button>
            </div>
            <CommentForm
                :visible="this.commentFormVisible"
                :parentId="this.comment.id"
                :toast_id="parseInt(this.comment.commentable_id)"
                v-on:closeCommentForm="this.toggleCommentForm"
            />
        </div>
        <div>
            <!-- Replies -->
            <Comment
                v-for="(reply, index) in this.replyList.filter(
                    (reply) => reply.child_id == this.comment.id
                )"
                :key="index"
                :comment="reply"
                :owner="this.owner"
                :owned="this.owner ? this.owner == reply.commenter_id : false"
            />
        </div>
    </div>
</template>

<script>
import CommentForm from "./CommentForm.vue";
import { mapGetters, mapActions } from "vuex";
import CommentTools from "./CommentTools.vue";
export default {
    name: "Comment",
    data() {
        return {
            commentFormVisible: false,
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
        owned: {
            type: Boolean,
            default: false,
        },
        owner: {
            type: Number,
            default: null,
        },
    },
    methods: {
        toggleCommentForm() {
            this.commentFormVisible = !this.commentFormVisible;
        },
    },
    components: { CommentForm, CommentTools },
};
</script>
