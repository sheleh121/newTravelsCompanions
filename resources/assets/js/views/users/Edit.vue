<template>
    <div>
        <section class="about-banner relative">
            <div class="overlay overlay-bg"></div>
            <div class="container">
                <div class="row d-flex align-items-center justify-content-center">
                    <div class="about-content col-lg-12">
                        <h1 class="text-white">
                            Редактировать профиль
                        </h1>
                    </div>
                </div>
            </div>
        </section>
        <div v-if="status !== null" class="alert alert-success alert-dismissible" >
            {{ status }}
            <button type="button" class="close" aria-label="Close">
                <span @click="status = null" aria-hidden="true">&times;</span>
            </button>
        </div>

        <div v-if="status_error !== null" class="alert alert-danger alert-dismissible">
            {{ status_error }}
            <button type="button" class="close" aria-label="Close">
                <span @click="status_error = null" aria-hidden="true">&times;</span>
            </button>
        </div>


        <div class="main-wrap">
            <div class="form-row">
                <div class="col-xs-12 col-lg-3">
                    <input type="text" class="form-control" placeholder="Фамилия" id="surname" name="surname" v-model="user.surname" >
                </div>
                <div class="col-xs-12 col-lg-3">
                    <input type="text" class="form-control" placeholder="Имя" id="name" name="name" v-model="user.name" >
                </div>
                <div class="col-xs-12 col-lg-3">
                    <input type="text" class="form-control" placeholder="Отчество" id="patronymic" name="patronymic" v-model="user.patronymic" >
                </div>
                <div class="col-xs-12 col-lg-3">
                    <button @click="fio()" type="submit" class="btn btn-primary">Сохранить</button>
                </div>

            </div>

        </div>


<!--        <div class="main-wrap">
            <label for="phone" class="form-label text-md-right">Номер телефона. Не отображается на сайте, необходим для регистрации и восстановления пароля</label>
            <div class="form-row">
                <div class="col-xs-12 col-lg-3">
                    <input id="phone" type="text" class="form-control" placeholder="Номер телефона" name="phone" v-model="user.phone"  >
                </div>
                <div class="col-xs-12 col-lg-3">
                    <button @click="phone()" type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            </div>
        </div>-->

        <div class="main-wrap">
            <label for="country" class="form-label text-md-right">Место проживания</label>
            <div class="form-row">
                <div class="col-xs-12 col-lg-3">
                    <select class="form-control" name = "country" id = "country" @change="GetRegion"  v-model="user.country" >
                        <option :value="0">страна</option>
                        <option v-for="option in countries" v-bind:value="option.id">
                            {{ option.name }}
                        </option>
                    </select>

                </div>
                <div class="col-xs-12 col-lg-3">
                    <select class="form-control" name = "region" id = "region" required @change="GetCities"  v-model="user.region" v-if="regionSeen">
                        <option :value="0">регион</option>
                        <option v-for="option in region" v-bind:value="option.id">
                            {{ option.name }}
                        </option>
                    </select>
                </div>
                <div class="col-xs-12 col-lg-3">
                    <select class="form-control" name = "city" id = "city" v-model="user.city" v-if="citiesSeen">
                        <option :value="0">город</option>
                        <option v-for="option in cities" v-bind:value="option.id">
                            {{ option.name }}
                        </option>
                    </select>

                </div>
                <div class="col">
                    <button @click="location()" type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            </div>
        </div>

        <div class="main-wrap">
            <label for="password_current" class="form-label text-md-right">Сменить пароль</label>
            <div class="form-row">
                <div class="col-xs-12 col-lg-3">
                    <input v-model="user.password_current" id="password_current" name="password_current" type="password" class="form-control" placeholder="текущий пароль" >
                </div>
                <div class="col-xs-12 col-lg-3">
                    <input v-model="user.password" id="password" name="password" type="password" class="form-control" placeholder="новый пароль" >
                </div>
                <div class="col-xs-12 col-lg-3">
                    <input v-model="user.password_confirm" id="password_confirm" name="password_confirmation" type="password" class="form-control" placeholder="подтверждение" >
                </div>
                <div class="col-xs-12 col-lg-3">
                    <button @click="password()" type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            </div>
        </div>
        <!--<div class="main-wrap">
            <label for="image" class="form-label text-md-right">Аватар</label>
            <div class="form-row">
                <div class="col-xs-12 col-lg-3">
                    <input type="file" name="image" id="image" class="input-file form-control" />
                </div>
                <div class="col-xs-12 col-lg-3">
                    <button @click="avatar()" type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            </div>
        </div>-->

        <div class="form-group row">
            <label for="image" class="col-md-3 col-form-label ">Изображение</label>
            <div class="col-md">
                <input @change="previewThumbnail" class="form-control-file" name="image" type="file">
            </div>
            <div class="col-md">
                <i v-show="! image_src" class="icon fa fa-picture-o"></i>
                <img v-show="image_src" class="img img-thumbnail" style="max-height: 200px; float: right" :src="image_src">
            </div>
            <div class="col-md">
                <button @click="avatar()" type="submit" class="btn btn-primary">Сохранить</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: [

        ],
        data: function (){
            return{
                auth_user: auth.user,
                user: {
                    name: '',
                    surname: '',
                    patronymic: '',
                    country: 0,
                    region: 0,
                    city: 0,
                    email: '',
                    phone: '',
                    password_current: '',
                    password: '',
                    password_confirm: '',

                },
                image_src: '',
                image: null,

                countries: Array,
                region: Array,
                cities: Array,

                regionSeen: true,
                citiesSeen: true,


                status: null,
                status_error: null
            }
        },
        mounted() {
            axios.get('/api/location/countries')
                .then((response) =>{
                    this.countries = response.data
                    axios.get('/api/location/region', {
                        params: {
                            country: this.user.country,
                        }}).then((response) =>{
                        this.region =response.data
                        axios.get('/api/location/cities', {
                            params: {
                                region: this.user.region
                            }}).then((response) =>{
                            this.cities =response.data
                        })
                    })
                })


            axios.get('/api/user/info').then((response) =>{
                this.user = response.data;
            })
        },
        methods: {
            GetCountries: function () {
                axios.get('/api/location/countries').then((response) =>{
                    this.countries = response.data
                })

            },
            GetRegion: function () {
                axios.get('/api/location/region', {
                    params: {
                        country: this.user.country,
                    }}).then((response) =>{
                    this.region =response.data
                    this.regionSeen = true
                    this.citiesSeen = false
                    this.user.region = this.user.city = 0
                })
            },
            GetCities: function () {
                axios.get('/api/location/cities', {
                    params: {
                        region: this.user.region
                    }}).then((response) =>{
                    this.cities =response.data
                    this.citiesSeen = true
                    this.selected.city = 0
                })
            },
            fio: function () {
                this.status = this.status_error = null;

                axios
                    .put('/api/user/fio', {
                        surname: this.user.surname,
                        name: this.user.name,
                        patronymic: this.user.patronymic
                    })
                    .then((response) => {
                        auth.getUser();
                        this.status = response.data;
                    }).catch(({response}) => {
                    this.status_error = response.data;
                });

            },
            email: function () {
                this.status = this.status_error = null;

                axios
                    .put('/api/user/email', {
                        email: this.user.email
                    })
                    .then((response) => {
                        auth.getUser();
                        this.status = response.data;
                    }).catch(({response}) => {
                    this.status_error = response.data;
                });

            },
            phone: function () {
                this.status = this.status_error = null;

                axios
                    .put('/api/user/phone', {
                        phone: this.user.phone
                    })
                    .then((response) => {
                        auth.getUser();
                        this.status = response.data;
                    }).catch(({response}) => {
                    this.status_error = response.data;
                });

            },
            location: function () {
                this.status = this.status_error = null;
                axios
                    .put('/api/user/location', {
                        country: this.user.country,
                        region: this.user.region,
                        city: this.user.city
                    })
                    .then((response) => {
                        auth.getUser();
                        this.status = response.data;
                    }).catch(({response}) => {
                    this.status_error = response.data;
                });

            },
            password: function () {
                this.status = this.status_error = null;

                axios
                    .put('/api/user/password', {
                        password_current: this.user.password_current,
                        password: this.user.password,
                        password_confirmation: this.user.password_confirm
                    })
                    .then((response) => {
                        auth.getUser();
                        this.status = response.data;
                    }).catch(({response}) => {
                    this.status_error = response.data;
                });

            },
            avatar: function () {
                let formData = new FormData();
                formData.append('image', this.image);
                this.status = this.status_error = null;

                axios
                    .post('/api/user/avatar', formData)
                    .then((response) => {
                        auth.getUser();
                        this.status = response.data;
                    }).catch(({response}) => {
                    this.status_error = response.data;
                });

            },
            previewThumbnail: function(event) {
                var input = event.target;


                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    var vm = this;
                    this.image = input.files[0];
                    reader.onload = function(e) {
                        vm.image_src = e.target.result;
                    };

                    reader.readAsDataURL(input.files[0]);
                }
            }

        }
    }
</script>
