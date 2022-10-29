<script>
import { VuemojiPicker } from 'vuemoji-picker'
export default {
    components: {
        VuemojiPicker
    },
    data() {
        return {
            newPost: {
                content: ''
            },
            opened: false,
            errors: undefined,
            success: "",
            imgName: '',
            openedPicker: false,
            pickerStyle: {
                width: '100%',
                numColumns: '8'
            }
        }
    },
    emits: ['create-post'],
    props: ['user_name'],
    methods: {
        handleEmojiClick(detail) {
            this.newPost.content += detail.unicode;
        },
        createPost() {
            const config = {
                headers: {
                    'content-type': 'multipart/form-data'
                }
            }
            this.openedPicker = false;
            axios.post('/posts/', this.newPost, config).then((response) => {
                this.errors = undefined;
                this.success = "Post created succesfully!";
                this.newPost = { content: '', image: undefined };
                this.imgName = '';
                this.$emit('create-post', response.data);
            }).catch(error => {
                this.errors = error.response.data.errors;
                this.success = ""
            });
        },
        removeImage() {
            this.newComment.image = undefined;
            this.imgName = '';
        },
        getImage(e) {
            if (e.target.files[0]) {
                let files = e.target.files[0] || e.dataTransfer.files;
                this.newPost.image = files;
                this.imgName = files.name;
            }
        }
    }
} 
</script>

<template>
    <div class="text-center"><button class="btn-bl tomato w-50" @click="opened = !opened">Create post</button></div>
    <div v-if="opened" class="card text-white">
        <div class="card-header bg-post">
            <div class="card-subtitle col-9 col-lg-10">
                <h4><a href="#">{{ user_name }}</a></h4>
            </div>
        </div>
        <div class="card-body bg-dark">
            <form @submit.prevent="createPost" enctype="multipart/form-data">
                <h4 class="card-title"><input type="text" v-model="newPost.title" placeholder="title"></h4>
                <textarea type="text" v-model="newPost.content" style="height: 100%; width: 100%"></textarea>
                <div v-if="imgName" class="my-2 py-2 d-flex align-items-center border ell">
                    <i class="tomato fa-solid fa-circle-xmark fa-lg m-1" @click="removeImage"></i>
                    <p class="text-white m-auto px-3">{{ imgName }}</p>
                </div>
                <label class="px-1 btn-custom tomato">
                    <i class="px-1 fa-solid fa-face-smile fa-lg" @click="openedPicker = !openedPicker"></i>
                </label>
                <label class="px-1 btn-custom tomato" for="image"><i class="fa-solid fa-image"></i><input type="file"
                        @change="getImage" class="no-style" id="image"></label>
                <label class="px-1 btn-custom tomato" for="sub">Post! <i class="fa-solid fa-paper-plane"></i><input
                        type="submit" class="no-style" id="sub"></label>
                <VuemojiPicker v-if="openedPicker" :pickerStyle="pickerStyle" @emojiClick="handleEmojiClick" />
            </form>
        </div>
        <div v-if="errors" class="alert alert-danger card-footer my-0">
            <p class="my-0" v-for="error in errors">{{ error }}</p>
        </div>
        <div v-else-if="success" class="alert alert-success card-footer my-0">
            <p class="my-0">{{ success }}</p>
        </div>
    </div>
</template>