<script>
import { VuemojiPicker } from 'vuemoji-picker'
export default {
    components: {
        VuemojiPicker
    },
    data() {
        return {
            errors: undefined, success: '', imgName: '', openedPicker: false,
            post: {
                updateImage: 0
            },
            pickerStyle: {
                width: '100%',
                numColumns: '8'
            }
        }
    },
    props: ['title', 'content', 'image', 'id'],
    mounted() {
        this.post.content = this.content;
        this.post.title = this.title;
        this.imgName = this.image;
    },
    methods: {
        handleEmojiClick(detail) {
            this.post.content += detail.unicode;
        },
        editPost() {
            this.post['_method'] = 'put';
            axios.post('/posts/' + this.id, this.post, {
                headers: {
                    'content-type': 'multipart/form-data'
                }
            }).then(response => {
                location.assign('/home');
            }).catch(error => {
                this.errors = error.response.data.errors;
            });
        },
        removeImage() {
            this.post.image = undefined;
            this.post.updateImage = 1;
            this.imgName = '';
        },
        getImage(e) {
            if (e.target.files[0]) {
                this.post.updateImage = 1;
                this.post.image = e.target.files[0] || e.dataTransfer.files;
                this.imgName = e.target.files[0].name;
            }
        }
    }
} 
</script>

<template>
    <div class="card text-white">
        <div class="card-header bg-post"></div>
        <div class="card-body bg-dark">
            <form @submit.prevent="editPost" enctype="multipart/form-data">
                <h5 class="card-title"><input type="text" v-model="post.title" placeholder="title" required></h5>
                <textarea type="text" v-model="post.content" style="height: 100%; width: 100%" required></textarea>
                <div v-if="imgName" class="my-2 py-2 d-flex align-items-center border ell">
                    <i class="tomato fa-solid fa-circle-xmark fa-lg m-1" @click="removeImage"></i>
                    <p class="text-white m-auto px-3">{{ imgName }}</p>
                </div>
                <label class="px-1 btn-custom tomato" @click="openedPicker = !openedPicker">
                    <i class="px-1 fa-solid fa-face-smile fa-lg"></i>
                </label>
                <label class="px-1 btn-custom tomato" for="image"><i class="fa-solid fa-image"></i><input type="file"
                        @change="getImage" class="no-style" id="image"></label>
                <label class="px-1 btn-custom tomato" for="sub">Edit! <i class="fa-solid fa-paper-plane"></i><input
                        type="submit" class="no-style" id="sub"></label>
                <VuemojiPicker v-if="openedPicker" :pickerStyle="pickerStyle" @emojiClick="handleEmojiClick" />
            </form>
        </div>
        <div v-if="errors" class="alert alert-danger card-footer my-0">
            <p class="my-0" v-for="error in errors">{{ error }}</p>
        </div>
    </div>
</template>