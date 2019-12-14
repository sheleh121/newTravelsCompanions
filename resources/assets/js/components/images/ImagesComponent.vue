<template>
<div >

 <images-carousel-component v-if="display" :images_id="images_id.data" :image_big = "image_big" :travel_id = "travel_id"/>
    <div v-if="display"  >
        <div >
                <img class="img-thumbnail float-left"
                     style="cursor: pointer; max-height: 340px"
                     v-auth-image="'/api/travels/' + travel_id + '/images/' + images_id.data[0].id + '/full'"
                     data-toggle="modal"
                     data-target="#image"
                     @click="image_big.id = images_id.data[0].id; image_big.key = 0"
                />
            <button class="btn btn-success" v-if="images_id.next_page_url" @click="Pagination()">ещё</button>
        </div>
    </div>
    <img v-else src="/images/default_travel.jpg"/>
</div>


</template>

<script>
    export default {
        props: [
            'travel_id'
        ],
        data: function () {
            return{
                images_id: [],
                image_big: {
                    id: 0,
                    key: 0
                },
                display: false
            }
        },
        mounted() {
            this.init();
            this.Pagination();
        },
        methods: {
            init: function () {
                axios
                    .get('/api/travels/' + this.travel_id +'/images')
                    .then((response) =>{
                        if (response.data.data.length > 0){
                            this.images_id = response.data;
                            this.display = true;
                            this.image_big.id = response.data[0].id
                        }
                    })
            },
            Pagination: function () {
                if (this.images_id.next_page_url) {
                    axios.get(this.images_id.next_page_url)
                        .then((response) =>{

                                response.data.data.forEach(function (data) {
                                    this.images_id.data.push(data)
                                }, this),
                                    this.images_id.next_page_url = response.data.next_page_url
                            }
                        )
                }
            },

        }
    }
</script>

