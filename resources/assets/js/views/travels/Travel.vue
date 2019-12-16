<template>
    <div>
        <section class="about-banner relative">
            <div class="overlay overlay-bg"></div>
            <div class="container">
                <div class="row d-flex align-items-center justify-content-center">
                    <div class="about-content col-lg-12">
                        <h1 class="text-white">

                        </h1>
                    </div>
                </div>
            </div>
        </section>

        <!-- Start insurence-one Area -->
        <section class="insurence-one-area section-gap">
            <div class="container">
                <div v-if="status !== null" class="alert alert-success alert-dismissible" role="alert">
                    {{ status }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div v-if="status_error !== null" class="alert alert-danger alert-dismissible" role="alert">
                    {{ status_error }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="row ">
                    <div class="col-lg-6 insurence-left">
                        <div>
                            <images-component :travel_id = "travel_id" />
                        </div>
                    </div>
                    <div class="col-lg-6 insurence-right">
                        <h6 class="text-uppercase">
                            <p v-if="travel.success === 1" style="color: #38c172">мероприятие состоялось</p>
                            <small v-else-if="travel.success === 0" style="color: #e3342f">отменено</small>
                            <small v-if="current_user_claim.claim === 6 && travel.success === null" style="color: #38c172">вы автор</small>
                            <small v-else-if="current_user_claim.claim === 5 && travel.success === null" style="color: #38c172">вы участник</small>
                            <small v-if="current_user_claim.claim === 3 && travel.success === null" style="color: #38c172">Вы подали заявку на участие</small>
                            <small v-if="current_user_claim.claim === 4 && travel.success === null" style="color: #38c172">Вас пригласили учавствовать</small>
                        </h6>
                        <h1>{{ travel.name }}</h1>
                        <p>
                            {{ travel.description }}
                        </p>
                        <div class="list-wrap">
                            <ul>
                                <li v-if="travel.commercial === 1" style="left: 0">Коммерческая основа</li>
                                <li><b>Где: </b>{{travel.country.name + ' ' + travel.region.name + ' ' + travel.city.name }}</li>
                                <li><b>C </b> {{travel.date_begin}} <b> по </b> {{travel.date_end}}</li>
                                <li><b>Тип: </b>{{travel.type.name}}</li>
                                <li><b>Категория: </b>{{travel.category.name}}</li>
                                <li><b>Автор: </b><a :href="'/users/' + travel.author.id" > {{travel.author.surname + ' ' + travel.author.name + ' ' + travel.author.patronymic}}</a></li>

                            </ul>
                        </div>

                    </div>
                    <div v-if="travel.success === null" class="col-lg-12 insurence-right">
                        <div class="button-group-area mt-40 float-right">
                            <router-link v-if="current_user_claim.claim === 6" class="genric-btn primary "
                                         :to="{ name: 'travel_edit', params: { travel_id: travel_id  }} ">
                                редактировать
                            </router-link>
                            <router-link v-if="current_user_claim.claim === 6" class="genric-btn primary "
                                         :to="{ name: 'travel_edit_images', params: { travel_id: travel_id, travel_name: travel.name }} ">
                                изображения
                            </router-link>

                            <button v-if="current_user_claim.claim === 6" @click="success(false)" class="genric-btn danger ">
                                отменить
                            </button>
                            <button v-if="current_user_claim.claim === 6" @click="success(true)" class="genric-btn success ">
                                состоялось
                            </button>


                            <!-- <button v-if="current_user_claim.claim !== 6" class="genric-btn success float-right">
                                 пожаловаться
                             </button>-->
                            <button v-if="current_user_claim.claim < 3" @click="claim()" class="genric-btn success float-right">
                                подать заявку
                            </button>
                            <button v-if="current_user_claim.claim === 4" @click="claimUpdate(5)" class="genric-btn success float-right" >
                                принять
                            </button>
                            <button v-if="current_user_claim.claim === 4" @click="claimUpdate(2)" class="genric-btn danger float-right" >
                                отклонить
                            </button>
                            <button v-if="current_user_claim.claim === 5" @click="claimUpdate(2)" class="genric-btn danger float-right" >
                                отказаться от участия
                            </button>
                        </div>


                    </div>
                </div>

                <!-- End insurence-one Area -->
                <div v-if="current_user_claim.claim > 4">
                    <travel-user-component v-if="current_user_claim.claim === 6 && travel.success == null" :travel_id = "travel_id" :author_status = "true" :author="travel.author"/>
                    <travel-user-component v-else :travel_id = "travel_id" :author = "false"/>
                </div>

            </div>

        </section>

    </div>
</template>

<script>
    export default {
        metaInfo () {
            return {
                title: this.travel.name,
                meta: [
                    { name: 'og:title', content: this.travel.name },
                    { name: 'og:site_name', content: 'Поиск компании в путешествие' },
                    { name: 'og:description', content: this.travel.description },
                    { name: 'og:image', content: 'https://travels-companions.info/img/preview.png' },
                    { name: 'description', content: this.travel.description },
                ],
            }

        },
        props: [
            'travel_id'
        ],
        data: function () {
            return {
                travel: {
                    country: Object,
                    region: Object,
                    city: Object,
                    type: Object,
                    category: Object,
                    author: Object,
                    name: '',
                    description: ''
                },
                author: [],
                current_user_claim: [],
                status: null,
                status_error: null
            }

        },
        mounted() {
            this.init()
        },
        methods: {
            init () {
                axios.get('/api/travels/' + this.travel_id).then((response) => {
                    this.travel = response.data.travel;
                    this.current_user_claim = response.data.current_user_claim

                }).catch(error => {
                    if (error.response.status === 401) {
                        this.$router.push({name: 'login'})
                    }
                });
            },
            success (success) {
                axios.put('/api/travels/' + this.travel_id + '/success', {
                    success: success,
                    travel_id: this.travel_id
                }).then((response) => {
                    this.init();
                    this.status = response.data;
                }).catch(({response}) => {
                    this.status_error = response.data;
                });
            },
            claim () {
                axios.post ('/api/travels/' + this.travel_id + '/claim', {
                    travel_id: this.travel_id
                }).then((response) => {
                    this.button = false;
                    this.status = response.data;
                }).catch(({response}) => {
                    if (response.status === 401) {
                        this.$router.push({name: 'login'})
                    }
                    else this.status_error = response.data;
                });
            },
            claimUpdate: function (new_claim) {
                axios
                    .put('/api/travels/' + this.travel.id + '/claim', {
                        claim_id: this.current_user_claim.id,
                        new_claim: new_claim
                    }).then(() => {
                    this.current_user_claim.claim = new_claim
                }).catch(({response}) => {
                    this.status_error = response.data;
                });
            },
        },

    }

</script>

<style scoped>

</style>
