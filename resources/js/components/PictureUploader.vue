<template>
    <div class="modal fade" id="img-upload" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-fullscreen-md-down">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Choose your profile picture</h2>
                    <a target="_blank" href="https://github.com/Agontuk/vue-cropperjs">Github (VueCropper)</a>
                </div>
                <div class="modal-body">
                    <input ref="input" type="file" name="image" accept="image/*" @change="setImage" />
                    <button class="rad" role="button" @click.prevent="showFileChooser">
                        Upload Image!
                    </button>
                    <button v-if="this.user_image != 'default.png'" class="rad" role="button" @click.prevent="rmvImage">
                        Remove current Image!
                    </button>
                    <div v-if="imgSrc" class="content container row">
                        <section class="cropper-area">
                            <div class="img-cropper">
                                <vue-cropper ref="cropper" :aspect-ratio="1 / 1" :src="imgSrc" preview=".preview" />
                            </div>
                            <div class="actions">
                                <button class="rad red" role="button" @click.prevent="zoom(0.2)">
                                    <i class="fa-solid fa-magnifying-glass-plus"></i>
                                </button>
                                <button class="rad red" role="button" @click.prevent="zoom(-0.2)">
                                    <i class="fa-solid fa-magnifying-glass-minus"></i>
                                </button>
                                <button class="rad blue" role="button" @click.prevent="move(-10, 0)">
                                    <i class="fa-solid fa-arrow-right"></i>
                                </button>
                                <button class="rad blue" role="button" @click.prevent="move(10, 0)">
                                    <i class="fa-solid fa-arrow-left"></i>
                                </button>
                                <button class="rad blue" role="button" @click.prevent="move(0, -10)">
                                    <i class="fa-solid fa-arrow-up"></i>
                                </button>
                                <button class="rad blue" role="button" @click.prevent="move(0, 10)">
                                    <i class="fa-solid fa-arrow-down"></i>
                                </button>
                                <button class="rad green" role="button" @click.prevent="rotate(90)">
                                    <i class="fa-solid fa-rotate-right"></i>
                                </button>
                                <button class="rad green" role="button" @click.prevent="rotate(-90)">
                                    <i class="fa-solid fa-rotate-left"></i>
                                </button>
                                <button ref="flipX" class="rad purple" role="button" @click.prevent="flipX">
                                    Flip X
                                </button>
                                <button ref="flipY" class="rad purple" role="button" @click.prevent="flipY">
                                    Flip Y
                                </button>
                                <button class="rad" role="button" @click.prevent="reset">
                                    Reset
                                </button>
                            </div>
                        </section>
                        <section class="preview-area mx-2">
                            <p>Preview</p>
                            <div class="preview" />
                        </section>
                    </div>
                    <div v-if="errors" class="alert alert-danger card-footer my-0">
                        <p class="my-0" v-for="error in errors">{{ error }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button v-if="imgSrc" type="submit" class="btn rad"
                            :data-bs-dismiss="this.errors ? 'modal' : ''" @click="cropImage">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import VueCropper from 'vue-cropperjs';
import 'cropperjs/dist/cropper.css';
import * as utils from '../utils';
export default {
    components: {
        VueCropper
    },
    props: ['user_id', 'user_image'],
    data() {
        return {
            imgSrc: '',
            cropImg: '',
            imgName: '',
            imgCropped: {},
            errors: ''
        };
    },
    methods: {
        rmvImage() {
            axios.put('/rmvimage/' + this.user_id).then(() => {
                location.reload();
            }).catch(error => {
                this.errors = error.response.data.errors;
            });
        },
        cropImage() {
            // get image data for post processing, e.g. upload or setting image src
            this.cropImg = this.$refs.cropper.getCroppedCanvas({ width: 800, height: 800, fillColor: 'white' }).toDataURL();
            // convert base64URL image obtained to an image file and save it to a variable
            this.imgCropped.image = utils.dataURLtoFile(this.cropImg, this.imgName);
            const config = {
                headers: {
                    'content-type': 'multipart/form-data'
                }
            }
            axios.post('/setimage', this.imgCropped, config).then(() => {
                location.reload();
            }).catch(error => {
                this.errors = error.response.data.errors;
            });
        },
        flipX() {
            const dom = this.$refs.flipX;
            let scale = dom.getAttribute('data-scale');
            scale = scale ? -scale : -1;
            this.$refs.cropper.scaleX(scale);
            dom.setAttribute('data-scale', scale);
        },
        flipY() {
            const dom = this.$refs.flipY;
            let scale = dom.getAttribute('data-scale');
            scale = scale ? -scale : -1;
            this.$refs.cropper.scaleY(scale);
            dom.setAttribute('data-scale', scale);
        },
        move(offsetX, offsetY) {
            this.$refs.cropper.move(offsetX, offsetY);
        },
        reset() {
            this.$refs.cropper.reset();
        },
        rotate(deg) {
            this.$refs.cropper.rotate(deg);
        },
        setImage(e) {
            const file = e.target.files[0];
            this.imgName = e.target.files[0].name;
            console.log(file);
            if (file.type.indexOf('image/') === -1) {
                alert('Please select an image file');
                return;
            }
            if (typeof FileReader === 'function') {
                const reader = new FileReader();
                reader.onload = (event) => {
                    // rebuild cropperjs with the updated source
                    if (this.imgSrc) this.$refs.cropper.replace(event.target.result);
                    this.imgSrc = event.target.result;
                };
                reader.readAsDataURL(file);
            } else {
                alert('Sorry, FileReader API not supported');
            }
        },
        showFileChooser() {
            this.$refs.input.click();
        },
        zoom(percent) {
            this.$refs.cropper.relativeZoom(percent);
        }
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
input[type="file"] {
    display: none;
}

.rad {
    border-radius: 10%;
    border: .1em solid;
    margin: .5em .5em .5em 0;
}

.rad:hover {
    color: tomato;
    border-color: tomato;
}

.red {
    color: red;
    border-color: red;
}

.blue {
    color: blue;
    border-color: blue;
}

.green {
    color: green;
    border-color: green;
}

.purple {
    color: purple;
    border-color: purple;
}

.content {
    display: flex;
    justify-content: space-between;
    width: 100%;
}

.cropper-area {
    width: 75%;
}

.preview {
    width: 100%;
    height: calc(372px * (9 / 16));
    overflow: hidden;
}

.preview-area {
    width: 307px;
    align-self: flex-start;
    justify-self: flex-start;
}

.preview-area p {
    font-size: 1.25rem;
    margin: 0;
    margin-bottom: 1rem;
}

.preview-area p:last-of-type {
    margin-top: 1rem;
}
</style>