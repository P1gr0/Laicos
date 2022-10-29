<template>
  <div class="row d-flex justify-content-between">
    <div class="mb-3 col-sm-4 text-center">
      <h3 class="text-shadow" style="color: goldenrod">Achievements <i class="fa-solid fa-trophy"></i></h3>
      <div class="my-3 p-3 w-50 card m-auto shadow rounded">
        <h5>{{ count.posts }}</h5>
        <p>Posts</p>
      </div>
      <div class="my-3 p-3 w-50 card m-auto shadow rounded">
        <h5>{{ count.comments }}</h5>
        <p>Comments</p>
      </div>
    </div>
    <div class="mb-3 col-sm-4 d-flex justify-content-center align-items-center">
      <button type="button" class="btn shadow" :data-bs-toggle="this.is_home ? 'modal' : ''"
        data-bs-target="#img-upload">
        <img class="rounded-circle img-fluid" :src="`/images/profile/${this.user_image || 'default.png'}`"
          data-holder-rendered="true">
      </button>
    </div>
    <div class="mb-3 col-sm-4 text-center">
      <h3 class="text-shadow" style="color: dodgerblue">Friends <i class="fa-solid fa-users"></i></h3>
      <friendship v-if="!this.is_home" :user_id="this.user_id"></friendship>
      <friends :user_id="this.user_id"></friends>
    </div>
    <div v-if="errors" class="alert alert-danger card-footer my-0">
      <p class="my-0" v-for="error in errors">{{ error }}</p>
    </div>
  </div>

  <!-- Modal -->
  <picture-uploader :user_image="this.user_image" :user_id="this.user_id" @set-img="showErrors"></picture-uploader>
</template>

<script>
import axios from 'axios';
export default {
  props: ["user_image", "user_id", "is_home"],
  data() {
    return {
      errors: '',
      count: {
        posts: 0,
        comments: 0
      }
    };
  },
  mounted() {
    this.getCounts();
  },
  methods: {
    showErrors(err) {
      this.errors = err;
    },
    getCounts() {
      axios.get("/getcounts/" + this.user_id).then((response) => {
        this.animate(response.data[0], "posts");
        this.animate(response.data[1], "comments");
      });
    },
    animate(count, element) {
      if (this.count[element] < count)
        setTimeout(() => {
          this.count[element]++;
          this.animate(count, element);
        }, 40);
    }
  }
}
</script>

<style scoped>
.btn {
  border-radius: 50%;
  height: fit-content;
}

.text-shadow {
  text-shadow: .1rem .1rem .1rem rgba(255, 99, 71, 0.477);
  color: tomato
}

.w-50 {
  min-width: fit-content;
}
</style>