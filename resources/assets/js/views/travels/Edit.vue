<template>
    <div>
        <section class="about-banner relative">
            <div class="overlay overlay-bg"></div>
            <div class="container">
                <div class="row d-flex align-items-center justify-content-center">
                    <div class="about-content col-lg-12">
                        <h1 class="text-white">
                            {{ travel.name }}
                        </h1>
                    </div>
                </div>
            </div>
        </section>
        <section class="insurence-one-area section-gap whole-wrap">
            <div class="container border-top-generic" >
                <div class="form-row" style="margin-bottom: 5px">
                    <div class="col">
                        <select class="form-control" name="travel_type_id_input" v-model="travel.travel_type_id">
                            <option v-for="type in types" :value="type.id">{{type.name}}</option>
                        </select>
                    </div>
                    <div class="col">
                        <select class="form-control" name="category_input" v-model="travel.travel_category_id" >
                            <option v-for="category in categories" :value="category.id">{{category.name}}</option>
                        </select>
                    </div>
                </div>

                <div class="input-group " style="margin-bottom: 5px">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Начало</span>
                    </div>
                    <input v-model ="date_begin.date" :class="'form-control' + ('date_begin' in errors ? ' is-invalid' : '')" id="date_begin" type="date">
                    <select :class="'form-control' + ('date_begin' in errors ? ' is-invalid' : '')" v-model="date_begin.hh" style="height: 40px;">
                        <option v-for="h in hh" :value="h">{{h}}</option>
                    </select>
                    <select :class="'form-control' + ('date_begin' in errors ? ' is-invalid' : '')" v-model="date_begin.mi" style="height: 40px;">
                        <option v-for="m in mi" :value="m">{{m}}</option>
                    </select>
                </div>
                <div class="input-group " style="margin-bottom: 5px">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Окончание</span>
                    </div>
                    <input v-model ="date_end.date" :class="'form-control' + ('date_begin' in errors ? ' is-invalid' : '')" id="date_end" type="date">
                    <select :class="'form-control' + ('date_begin' in errors ? ' is-invalid' : '')" v-model="date_end.hh" style="height: 40px;">
                        <option v-for="h in hh" :value="h">{{h}}</option>
                    </select>
                    <select :class="'form-control' + ('date_begin' in errors ? ' is-invalid' : '')" v-model="date_end.mi" style="height: 40px;">
                        <option v-for="m in mi" :value="m">{{m}}</option>
                    </select>
                </div>

                <textarea style="margin-top: 5px" v-model="travel.description" :class="'form-control' + ('description' in errors  ? ' is-invalid' : '') "
                          rows="10" name="description_input" placeholder="Описание" >{{ travel.description }} </textarea>
                <span class="invalid-feedback" role="alert">
                    <strong>Минимум 20 символов</strong>
                </span>
                <select style="margin-top: 5px" class="form-control" name="decision_input" v-model="travel.decision">
                    <option value="1">Событие видят все посетители</option>
                    <option value="2">Событие видят зарегистрированные</option>
                    <option value="3">Событие видят участники</option>
                </select>

                <div class="container" style="margin-top: 5px">
                    <button @click="TravelUpdate()" class="genric-btn success float-right">Сохранить</button>
                    <button @click="$router.go(-1)" class="genric-btn danger">Отмена</button>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
    export default {
        props: [
            'travel_id'
        ],
        data: function (){
            return{
                date_begin: {
                    date: '',
                    hh: '',
                    mi: ''
                },
                date_end: {
                    date: '',
                    hh: '',
                    mi: ''
                },
                hh: ['00','01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23'],
                mi: ['00','01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23',
                    '24','25','26','27','28','29','30','31','32','33','34','35','36','37','38','39','40','41','42','43','44','45','46','47',
                    '48','49','50','51','52','53','54','55','56','57','58','59'
                ],
                travel: [],
                categories: [],
                types: [],
                errors: [],

            }
        },
        mounted() {
            this.Init()
        },
        methods: {
            Init: function () {
                axios
                    .get('/api/travels/' + this.travel_id)
                    .then((response) =>{
                        this.travel = response.data.travel

                        this.date_begin.date = this.travel.date_begin.substring(0,10)
                        this.date_begin.hh = this.travel.date_begin.substring(11,13)
                        this.date_begin.mi = this.travel.date_begin.substring(14,16)
                        this.date_end.date = this.travel.date_end.substring(0,10)
                        this.date_end.hh = this.travel.date_end.substring(11,13)
                        this.date_end.mi = this.travel.date_end.substring(14,16)

                    })
                axios.get('/api/travels/categories').then((response) =>{
                    this.categories = response.data
                })
                axios.get('/api/travels/types').then((response) =>{
                    this.types = response.data
                })
            },

            TravelUpdate: function () {
                axios
                    .put('/api/travels/' + this.travel_id, {
                        type: this.travel.travel_type_id,
                        category: this.travel.travel_category_id,
                        decision: this.travel.decision,
                        description: this.travel.description,
                        date_begin: this.date_begin.date + ' ' + this.date_begin.hh + ':' + this.date_begin.mi,
                        date_end: this.date_end.date + ' ' + this.date_end.hh + ':' + this.date_end.mi
                    })
                    .then(() =>{
                        this.$router.go(-1)
                    }).catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors;
                    }
                });
            }
        }
    }
</script>
