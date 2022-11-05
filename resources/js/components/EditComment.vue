<script>
import { VuemojiPicker } from 'vuemoji-picker'
export default {
    components: {
        VuemojiPicker
    },
    data() {
        return {
            errors: undefined, success: '', imgName: '', openedPicker: false,
            comment: {
                updateImage: 0
            },
            pickerStyle: {
                width: '100%',
                numColumns: '8'
            }
        }
    },
    props: ['content', 'image', 'id'],
    mounted() {
        this.comment.content = this.content;
        this.imgName = this.image;
    },
    methods: {
        handleEmojiClick(detail) {
            this.comment.content += detail.unicode;
        },
        editComment() {
            this.comment['_method'] = 'put';
            axios.post('/comments/' + this.id, this.comment, {
                headers: {
                    'content-type': 'multipart/form-data'
                }
            }).then(response => {
                history.back();
            }).catch(error => {
                this.errors = error.response.data.errors;
            });
        },
        removeImage() {
            this.comment.image = undefined;
            this.comment.updateImage = 1;
            this.imgName = '';
        },
        getImage(e) {
            if (e.target.files[0]) {
                this.comment.updateImage = 1;
                this.comment.image = e.target.files[0] || e.dataTransfer.files;
                this.imgName = e.target.files[0].name;
            }
        }
    }
} 
</script>

<template>
    <div class="card comment-cre">
        <div class="card-body p-2">
            <form @submit.prevent="editComment" enctype="multipart/form-data">
                <textarea type="text" v-model="comment.content" rows="3" style="height: 100%; width: 100%"
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
                    <label class="px-1 btn-custom tomato" for="sub">Edit!
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
    </div>
</template>

