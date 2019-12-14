<template>
    <div>
        <div v-if="image_big.id !== 0" class="modal fade" id="image" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document" style="max-width: 800px; ">
                <div class="modal-content">
                    <button class="btn btn-link" style="position: absolute; right: -40px; " data-dismiss="modal">
                        <img src="/images/cancel.png" style="width: 20px"/>
                    </button>
                    <img style="width: 100%" v-auth-image="'/api/travels/' + travel_id + '/images/' + image_big.id + '/full'"/>

                    <button class="btn btn-link" style="position: absolute; left: 0; height: 100%" @click="Prev()">
                        <img src="/images/prev.png" style="width: 20px"/>
                    </button>
                    <button class="btn btn-link" style="position: absolute; right: 0; height: 100%" @click="Next()">
                        <img src="/images/next.png" style="width: 20px"/>
                    </button>
                </div>
            </div>
        </div>
    </div>


</template>

<script>
    export default {
        props: [
            'images_id'
            ,'image_big'
            ,'travel_id'
        ],
        mounted() {

        },
        methods: {
            Next () {
                if (this.image_big.key < this.images_id.length - 1)
                    this.image_big.key ++;
                else
                    this.image_big.key = 0;
                this.image_big.id = this.images_id[this.image_big.key].id;
            },
            Prev () {
                if (this.image_big.key > 0 )
                    this.image_big.key --;
                else
                    this.image_big.key = this.images_id.length - 1;
                this.image_big.id = this.images_id[this.image_big.key].id;
            }
        }
    }
</script>

