<template>
    <div>
        <section class="about-banner relative">
            <div class="overlay overlay-bg"></div>
            <div class="container">
                <div class="row d-flex align-items-center justify-content-center">
                    <div class="about-content col-lg-12">
                        <h1 class="text-white">
                            пользователи
                        </h1>
                    </div>
                </div>
            </div>
        </section>


        <section class="destinations-area section-gap">
            <div class="container">
                <div class="row">

                    <div v-for="user in users.data" @click="getUser(user.id)" class="col-lg-4" style="cursor: pointer;" >

                        <div class="single-destinations">
                            <div class="details ">
                                <div class="row">
                                    <div class="col-4">
                                        <img v-auth-image="'/api/users/' + user.id + '/avatar/small'" class=" round" style="height: 63px;" >
                                    </div>

                                    <h4 class="col-8">

                                        {{user.surname + ' ' + user.name + ' ' + user.patronymic}}
                                    </h4>
                                </div>

                                <ul class="package-list">
                                    <li class="d-flex justify-content-between align-items-center">
                                        <span>Страна: </span>
                                        <span>{{user.country.name}}</span>
                                    </li>
                                    <li class="d-flex justify-content-between align-items-center">
                                        <span>Регион: </span>
                                        <span>{{user.region.name}}</span>
                                    </li>
                                    <li class="d-flex justify-content-between align-items-center">
                                        <span>Город: </span>
                                        <span>{{user.city.name}}</span>
                                    </li>
                                    <li class="d-flex justify-content-between align-items-center">
                                        <span>Карма: </span>
                                        <span>{{user.karma}}</span>
                                    </li>

                                </ul>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>



    </div>
</template>

<script>
    export default {
        data: function (){
            return{
                users: [],
                selected: []
            }
        },
        mounted() {
            this.GetUsers()
        },
        watch: {
            selected: {
                handler: function() {
                    this.GetUsers()
                },
                deep: true
            }
        },
        methods: {
            GetUsers: function () {
                axios.get('/api/users', {
                    params:{
                        country: this.selected.country,
                        region: this.selected.region,
                        city: this.selected.city
                    }
                }).then((response) =>{
                    this.users = response.data
                })
            },
            selectedLocation: function (data) {
                this.selected = data
            },
            getUser: function (userId) {
                this.$router.push('/users/' + userId)
            },
            Pagination: function () {
                window.onscroll = () => {
                    let bottomOfWindow = document.documentElement.scrollTop + window.innerHeight === document.documentElement.offsetHeight;
                    if (bottomOfWindow) {
                        axios.get(this.users.next_page_url)
                            .then((response) =>{
                                if (this.users.next_page_url) {
                                    response.data.data.forEach(function (data) {
                                        this.users.data.push(data)
                                    }, this),
                                        this.users.next_page_url = response.data.next_page_url
                                }
                            })
                    }
                };
            }
        }
    }
</script>
