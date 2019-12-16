<template>
    <div>
        <section class="about-banner relative">
            <div class="overlay overlay-bg"></div>
            <div class="container">
                <div class="row d-flex align-items-center justify-content-center">
                    <div class="about-content col-lg-12">
                        <h3 class="text-white">
                            <router-link :to="{ name: 'travel', params: { travel_id: travel_id  }} " >{{ travel_name }}</router-link>
                        </h3>
                    </div>
                </div>
            </div>
        </section>
        <section class="insurence-one-area section-gap whole-wrap">
            <div class="container border-top-generic" >

                <div class="main-wrap">
                    <div class="row">
                        <div class="col-lg">
                            <div class="example-2 float-left">
                                <input type="file" multiple="" name="file" id="file" class="input-file" @change="fileInputChange" />
                                    <label for="file" class="js-labelFile">
                                      <span class="genric-btn primary-border">Загрузить</span>
                                    </label>
                            </div>
                        </div>
                        <div class="col-lg">
                            <select class="genric-btn success float-right" @change="updateDecisionChecked" v-model="decision" style="height: 42px; padding: 1px; width: 60%">
                                <option :value="0" class="genric-btn success-border "  style="font-size: 1.2em;" selected>выбранные</option>
                                <option :value="1" class="genric-btn success-border " style="font-size: 1.2em;">
                                    видны всем посетителям
                                </option>
                                <option :value="2" style="font-size: 1.2em;" class="genric-btn success-border ">
                                    видны только зарегистрированным
                                </option>
                                <option :value="3" style="font-size: 1.2em;" class="genric-btn success-border ">
                                    видны только участникам
                                </option>
                            </select>
                            <button :class="'genric-btn float-right ' + (checked_all ? 'success' : 'success-border')"  @click="checkedAll()" style="width: 40%">
                                {{ checked_all ? 'снять выбор' : 'выбрать все' }}
                            </button>
                        </div>
                    </div>
                    </div>
                <div class="container">
                        <images-carousel-component :images_id="images" :image_big = "image_big" :travel_id = "travel_id"/>
                        <div class="image-edit img-thumbnail" v-for="(image, index) in sortImages" :key="image.id" >
                            <div>
                                <input type="checkbox" id="checkbox" v-model="image.checked" style="position: absolute; left: 10px; top: 10px" >
                                <button class="btn btn-link" style="position: absolute; right: 0" @click="deleteImage(image.id, 1, index)">
                                    <img src="/images/delete.png" style="width: 20px"/>
                                </button>
                                <img
                                    style="cursor: pointer"
                                    v-auth-image="'/api/travels/' + travel_id + '/images/' + image.id + '/small'"
                                    data-toggle="modal"
                                    data-target="#image"
                                    @click="image_big.id = image.id; image_big.key = index"
                                />
                                <select style="font-size: small; width: 100%" id = "'decision_image_id_' + image.id" v-model="image.decision" @change="updateDecision(image.id, image.decision)">
                                    <option style="font-size: small"  value="1">Видят все</option>
                                    <option style="font-size: small"  value="2">Только зарегистрированные</option>
                                    <option style="font-size: small"  value="3">Видят только участники</option>
                                </select>
                            </div>
                        </div>
                        <button class="btn btn-block btn-light" v-if="next_page_url" @click="Pagination()">ещё</button>
                </div>
            </div>
        </section>

    </div>

</template>

<script>
    export default {
        props: [
            'travel_id',
            'travel_name'
        ],
        data: function () {
            return{
                images: [],
                download_images: [],
                next_page_url: '',
                image_big: {
                    id: 0,
                    key: 0
                },
                checked_all: false,
                decision: 0
            }
        },
        mounted() {
            this.init()
        },
        computed: {
            sortImages() {
                return this.images.sort((a, b) => b.id - a.id);
            }
        },
        methods: {
            init: function () {
                axios
                    .get('/api/travels/'  + this.travel_id + '/images')
                    .then((response) =>{
                        response.data.data.forEach(function (data) {
                            data.checked = false;
                            this.images.push(data)
                        }, this),
                            this.next_page_url = response.data.next_page_url
                    })
            },
            async fileInputChange(event) {
                let files = Array.from(event.target.files);
                this.download_images = files.slice();

                for(let item of files){
                    await this.uploadFile(item)
                }
            },
            async uploadFile(item){
                let form = new FormData();
                form.append('image', item);
                form.append('travel_id', this.travel_id);

                await axios.post('/api/travels/'  + this.travel_id + '/images', form)
                    .then((response) =>{
                        response.data.checked = false;
                        this.images.unshift(response.data)
                        this.download_images.splice(item, 1)
                    })
            },
            updateDecision(image_id, decision){
                axios.put('/api/travels/'  + this.travel_id + '/images/decision', {
                    image_id: image_id,
                    decision: decision
                }).then((response) =>{

                })
            },
            updateDecisionChecked(){
                if (this.decision !== 0) {
                    this.images.forEach(function (data) {
                        if (data.checked){
                            this.updateDecision(data.id, this.decision)
                            data.decision = this.decision
                        }
                    }, this)
                    this.decision = 0;
                }

            },
            deleteImage(image_id, array_images, index){
                axios.delete('/api/images/' +image_id)
                    .then(() =>{
                        this.images.splice(index, 1)
                    })
            },
            deleteImageChecked(){
                this.images.forEach(function (data, index) {
                    if (data.checked)
                        this.deleteImage(data.id, 1, index)
                }, this)
            },
            checkedAll: function () {
                if (this.checked_all)
                    this.checked_all = false;
                else
                    this.checked_all = true;
                this.images.forEach(function (data) {
                    data.checked = this.checked_all;
                }, this)
            },
            Pagination: function () {
                if (this.next_page_url) {
                    axios.get(this.next_page_url).then((response) =>{
                            response.data.data.forEach(function (data) {
                                data.checked = false;
                                this.images.push(data)
                            }, this),
                                this.next_page_url = response.data.next_page_url
                        }
                    )
                }
            }
        }
    }
</script>

