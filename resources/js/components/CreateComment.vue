<script>
import { VuemojiPicker } from 'vuemoji-picker'
export default {
    components: {
        VuemojiPicker
    },
    data() {
        return {
            errors: undefined, success: '', imgName: '', openedPicker: false,
            newComment: {
                content: ''
            },
            pickerStyle: {
                width: '100%',
                numColumns: '8'
            }
        }
    },
    props: ['post_id'],
    methods: {
        handleEmojiClick(detail) {
            this.newComment.content += detail.unicode;
        },
        createComment() {
            const config = {
                headers: {
                    'content-type': 'multipart/form-data'
                }
            }
            this.openedPicker = false;
            this.newComment.post_id = this.post_id;
            axios.post('/comments/', this.newComment, config).then(response => {
                this.errors = undefined;
                this.newComment = { content: '', image: undefined };
                this.imgName = '';
                this.success = "Wonderful! You posted a comment!";
                this.$emit('create-comment', response.data);
            }).catch(error => {
                this.errors = error.response.data.errors;
                this.success = '';
            });
        },
        removeImage() {
            this.newComment.image = undefined;
            this.imgName = '';
        },
        getImage(e) {
            if (e.target.files[0]) {
                this.newComment.image = e.target.files[0] || e.dataTransfer.files;
                this.imgName = e.target.files[0].name;
            }
        }
    }
} 
</script>

<template>
    <div class="card comment-cre">
        <div class="card-body p-2">
            <form @submit.prevent="createComment" enctype="multipart/form-data">
                <textarea type="text" v-model="newComment.content" rows="3" style="height: 100%; width: 100%"
                    required></textarea>
                <div v-if="imgName" class="my-2 py-2 d-flex align-items-center border ell">
                    <i class="tomato fa-solid fa-circle-xmark fa-lg m-1" @click="removeImage"></i>
                    <p class="text-white m-auto px-3">{{ imgName }}</p>
                </div>
                <div class="d-flex align-items-center my-0">
                    <label class="px-1 btn-custom tomato" @click="openedPicker = !openedPicker">
                        <i class="px-1 fa-solid fa-face-smile fa-lg"></i>
                    </label>
                    <label class="px-1 btn-custom tomato" for="image">
                        <i class="fa-solid fa-image"></i>
                        <input type="file" @change="getImage" class="no-style" id="image">
                    </label>
                    <label class="px-1 btn-custom tomato" for="sub">Comment!
                        <i class="fa-solid fa-paper-plane"></i>
                        <input type="submit" class="no-style" id="sub">
                    </label>
                </div>
                <VuemojiPicker v-if="openedPicker" :pickerStyle="pickerStyle" @emojiClick="handleEmojiClick" />
            </form>
        </div>
        <div v-if="errors" class="card-footer alert alert-danger my-0">
            <p class="my-0" v-for="error in errors">{{ error }}</p>
        </div>
        <div v-else-if="success" class="alert alert-success card-footer my-0">
            <p class="my-0">{{ success }}</p>
        </div>
    </div>
</template>
