<template>
    <div class="card my-4">
        <div class="card-header bg-post">
            <div class="row d-flex align-items-center">
                <div class="col-2 col-lg-1">
                    <img class="rounded-circle img-fluid"
                        :src="`/images/profile/${this.user_image}`" data-holder-rendered="true">
                </div>
                <div class="card-subtitle col-9 col-lg-10">
                    <h4><a :href="`/users/${this.user_id}`">{{ user_name }}</a></h4>
                    <small>@{{ getTimeFromNow(created_at) }}</small>
                </div>
                <author-menu v-if="is_author" @update="updatePost" @remove="removePost">Post</author-menu>
            </div>
        </div>
        <div class="card-body bg-dark text-white">
            <h5 class="fst-italic card-title">{{ title }}</h5>
            <img v-if="image" :src="`/images/${this.image}`" class="card-img mb-2" alt="...">

           <!--  <div class="ratio ratio-16x9">
                <iframe :src="img_path" title="YouTube video" allowfullscreen></iframe>
            </div> -->

            <p class="card-text fs-6" v-html="linkify(content)"></p>
        </div>

        <div class="card-footer bg-post d-flex justify-content-between">
            <like :baseUrl="'/posts'" :id="id"></like>

            <div class="tomato">
                <p @click="showPost" class="my-0">
                    <i class="fa-regular fa-comment"></i>
                    Comment
                </p>
            </div>
        </div>
    </div>
</template>

<script>
import * as utils from '../utils';
export default {
    props: ['title', 'user_name', 'created_at', 'image', 'content', 'id', 'is_author', 'user_image', 'user_id'],
    methods: {
        linkify(content) {
            return utils.linkify(content);
        },
        getTimeFromNow(creationDate){
            return utils.timeSince(new Date(creationDate));
        },
        removePost() {
            if (confirm('Post will be removed permanently along with content. Are you sure?'))
            axios.delete("/posts/" + this.id).then(() => {
                this.$emit("delete-post", this.id);
            });
        },
        showPost() {
            location.assign("/posts/" + this.id);
        },
        updatePost() {
        }
    }
}
</script>