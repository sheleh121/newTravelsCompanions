<template>
    <div>
        <section class="about-banner relative">
            <div class="overlay overlay-bg"></div>
            <div class="container">
                <div class="row d-flex align-items-center justify-content-center">
                    <div class="about-content col-lg-12">
                        <h3 class="text-white">
                            Изображения события: "<router-link :to="{ name: 'travel', params: { travel_id: travel_id  }} " >{{ travel_name }}</router-link>"
                        </h3>
                    </div>
                </div>
            </div>
        </section>
        <div class="main-wrap">
        </div>
        <div class="main-wrap" style="margin-top: 20px; margin-bottom: 20px; border-color: #dae0e5">
            <div class="row">
                <div class="col">
                    <div class="example-2 float-left">
                        <input type="file" multiple="" name="file" id="file" class="input-file" @change="fileInputChange" />
                            <label for="file" class="js-labelFile">
                              <span class="btn btn-success ">Загрузить</span>
                            </label>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-light dropdown-toggle float-right" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            выбранные
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <!-- <button type="button" class="dropdown-item btn btn-success btn" @click="deleteImageChecked()">удалить</button>-->
                            <button type="button" class="dropdown-item btn btn-success btn" @click="updateDecisionChecked(1)">видны всем посетителям</button>
                            <button type="button" class="dropdown-item btn btn-success btn" @click="updateDecisionChecked(2)">видны только зарегистрированным</button>
                            <button type="button" class="dropdown-item btn btn-success btn" @click="updateDecisionChecked(3)">видны только участникам</button>
                        </div>
                    </div>
                    <button class="btn btn-light float-right"  @click="checkedAll()"><div v-if="checked_all">снять выбор</div><div v-if="checked_all == false">выбрать все</div></button>
                </div>
            </div>
            <div style="overflow-y: scroll; overflow-x: hidden; height: 400px;">

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
            updateDecisionChecked(decision_checked){
                this.images.forEach(function (data) {
                    if (data.checked){
                        this.updateDecision(data.id, decision_checked)
                        data.decision = decision_checked
                    }
                }, this)
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

