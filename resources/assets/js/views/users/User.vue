<template>
    <div>
        <div v-if="no_data_travels_for_auth_user" class="modal fade" id="users" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <button v-for="travel in travels_for_auth_user" @click="invite(travel.id)" class="dropdown-item btn btn-success "
                                style="border-radius: 0px;  float: left" >{{travel.name}}</button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                    </div>
                </div>
            </div>
        </div>
        <section class="about-banner relative">
            <div class="overlay overlay-bg"></div>
            <div class="container">
                <div class="row d-flex align-items-center justify-content-center">
                    <div class="about-content col-lg-12">


                            <img class="round" v-auth-image="'/api/users/' + user_info.id + '/avatar/small'">

                        <h4 class="text-white">
                            {{ user_info.surname + ' ' + user_info.name + ' ' + user_info.patronymic }}
                        </h4>
                        <h6 class="text-white">
                            {{'Карма: ' + user_info.karma}}
                        </h6>
                        <h6 class="text-white">
                            {{'Проживает: ' +  user_info.country.name + ', ' + user_info.region.name + ', ' + user_info.city.name }}
                        </h6>
                    </div>

                </div>
                <div class="row d-flex align-items-center justify-content-center">
                    <div class="about-content col-lg-12" style="margin-top: 0; padding: 0 0 10px 0">
                    <button v-if="no_data_travels_for_auth_user && (auth_user.id !== user_info.id)" class="genric-btn success" data-toggle="modal" data-target="#users">
                        пригласить
                    </button>
                    <button v-if="auth_user.id !== user_info.id" @click="dialog()" class="genric-btn success">cообщение</button>
                    <button v-if="auth_user.id === user_info.id" @click="$router.push({name: 'user_edit'})" class="genric-btn success">редактировать профиль</button>
                    <button v-if="auth_user.id === user_info.id" @click="logout()" class="genric-btn danger">выйти</button>
                    </div>
                </div>
            </div>
        </section>
        <section class="destinations-area section-gap">
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
            <div class="container">
                <div class="row">
                    <travel-component v-for="travel_user in travels_user" v-if="travel_user.travel !== null" :travel="travel_user.travel"/>
                </div>
            </div>
        </section>

    </div>
</template>

<script>
    export default {
        props: ['user_id'],
        data: function (){
            return{
                auth_user: auth.user,

                status: null,
                status_error: null,

                travels_for_auth_user: Object,
                user_info: {
                    city: Object,
                    city_id: 0,
                    country: Object,
                    country_id: 0,
                    created_at: '',
                    id: 0,
                    image_id: 0,
                    karma: 0,
                    name: '',
                    patronymic: '',
                    region: Object,
                    region_id: 0,
                    surname: '',
                    updated_at: '',
                    user_id: 0
                },
                travels_user: Object,
                next_page_url: '',
                no_data_travels_for_auth_user: false,
            }
        },
        mounted() {
            this.GetData();
            this.Pagination()

        },
        watch: {
            user_id: {
                handler: function() {
                    this.GetData();
                    this.Pagination();
                },
            }
        },
        methods: {
            logout() {
                auth.logout()
            },
            GetData: function () {
                axios.get('/api/users/' + this.user_id + '/travels/all').then((response) =>{
                    this.travels_user = response.data.data;
                    this.next_page_url = response.data.next_page_url;
                })
                axios.get('/api/users/' + this.user_id).then((response) =>{
                    this.user_info = response.data;
                })
                axios.get('/api/users/' + this.user_id + '/travels/author').then((response) =>{
                    if (response.data.length > 0)
                        this.no_data_travels_for_auth_user = true;
                    this.travels_for_auth_user = response.data;
                })


            },
            dialog: function () {
                axios
                    .post('/api/chat/rooms/new', {
                        user_invited: this.user_id
                    }).then(() => {
                    this.$router.push({name: 'messages'})
                });
            },
            invite: function (travel_id) {
                axios
                    .put('/api/travels/' + travel_id + '/invite', {
                        user_invited: this.user_id
                    }).then((response) => {
                    this.status = response.data;
                }).catch(({response}) => {
                    this.status_error = response.data;
                });
            },
            Pagination: function () {
                window.onscroll = () => {
                    let bottomOfWindow = document.documentElement.scrollTop + window.innerHeight === document.documentElement.offsetHeight;
                    if (bottomOfWindow) {
                        if (this.next_page_url !== null) {
                            axios.get(this.next_page_url)
                                .then((response) =>{
                                    response.data.data.forEach(function (data) {
                                        this.travels_user.push(data)
                                    }, this),
                                        this.next_page_url = response.data.next_page_url
                                })
                        }
                    }
                };
            }
        }
    }
</script>
<style>


</style>
