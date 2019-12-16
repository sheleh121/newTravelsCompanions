<template>
    <div>
        <section  class="banner-area relative" >
            <div class="overlay overlay-bg"></div>
            <div class="container">
                <div class="row align-items-center justify-content-between" :style=" $root.mobile ? ('height:' + $root.innerHeight + 'px') : 'height: 580px'">
                    <div class="col-lg-6 col-md-6 banner-left" >
                        <h6 class="text-white">Мы верим, что тот, кто ищет, тот всегда найдет</h6>
                        <h1 class="text-white">Найди компанию в приключение</h1>
                        <p class="text-white">
                            Создай мероприятие по своему вкусу или найди подходящее среди существующих.
                        </p>
                        <router-link :to="{ name: 'travel_new' }" class="primary-btn text-uppercase">Добавить мероприятие</router-link>
                    </div>
                    <div class="col-lg-4 col-md-6 banner-right" style="float: right">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="flight" role="tabpanel" aria-labelledby="flight-tab">
                                <div class="form-wrap">
                                    <!--<input type="checkbox" class="form-check-input" id="commercial" v-model="selected.commercial">-->
                                 <!--   <label class="form-check-label" for="commercial">Отображать события на коммерческой основе</label>-->
                                    <select class="form-control" v-model="selected.travel_type_id">
                                        <option :value="0" selected>что ищем</option>
                                        <option v-for="option in types" v-bind:value="option.id">
                                            {{ option.name }}
                                        </option>
                                    </select>
                                    <select class="form-control" v-model="selected.travel_category_id">
                                        <option :value="0" selected>тип</option>
                                        <option v-for="option in categories" v-bind:value="option.id">
                                            {{ option.name }}
                                        </option>
                                    </select>

                                        <div class="label">С</div>

                                    <input type="date" class="form-control" v-model="selected.date_begin">

                                        <div class="lable">ПО</div>

                                    <input type="date" class="form-control" v-model="selected.date_end">
                                    <location-component @selectedlocation = 'selectedLocation' :input_group = false  :default_country="false"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Start destinations Area -->
        <section class="destinations-area section-gap">
            <div class="container">
                <div class="row">
                    <travel-component v-for="travel in travels.data" v-if="travel !== null" :travel="travel"/>
                </div>
            </div>
        </section>
        <!-- End destinations Area -->
    </div>

</template>

<script>
    export default {
        data: function (){
            return{
                travels: Array,
                types: Array,
                categories: Array,

                selected: {
                    commercial: Boolean,
                    travel_type_id: 0,
                    travel_category_id: 0,
                    country_id: 0,
                    region_id: 0,
                    city_id: 0,
                    date_begin: null,
                    date_end: null,
                },

                no_data: false,
                window_width: 0,
                mobile_filter: false
            }
        },
        mounted() {
            this.GetData()
            this.Pagination()
            this.getWidth()
        },
        watch: {
            selected: {
                handler: function() {
                    this.GetTravels('/api/travels')
                },
                deep: true
            }
        },
        filters: {
            capitalize: function(text, length, clamp){
                clamp = clamp || '...';
                var node = document.createElement('div');
                node.innerHTML = text;
                var content = node.textContent;
                return content.length > length ? content.slice(0, length) + clamp : content;
            }
        },
        methods: {
            getWidth: function(){
                this.window_width = window.innerWidth;
            },
                GetData: function () {
                axios.get('/api/travels/categories').then((response) =>{
                    this.categories = response.data
                })
                axios.get('/api/travels/types').then((response) =>{
                    this.types = response.data
                })
                this.GetTravels('/api/travels')
            },
            selectedLocation: function (location) {
                this.selected.country_id = (location.country === null ? 0 : location.country.id);
                this.selected.region_id = location.region === null ? 0 : location.region.id;
                this.selected.city_id = location.city === null ? 0 : location.city.id;

            },
            GetTravels: function (url) {
                var params = {};
                for (var select in this.selected){
                    if (this.selected.hasOwnProperty(select)) {
                        if (select === 'commercial' && this.selected[select] === false)
                            params['commercial'] = 0;
                        else if (this.selected[select] !== 0 && this.selected[select] !== null && this.selected[select] !== '')
                            params[select] = this.selected[select];
                    }
                }
                axios.get(url, {
                    params: params
                }).then((response) =>{
                    this.travels =response.data
                    if (this.travels.data.length > 0)
                        this.no_data = false;
                    else
                        this.no_data = true;
                })
            },
            getTravel: function (travelId) {
                this.$router.push('travels/' + travelId)
            },
            Pagination: function () {
                window.onscroll = () => {
                    let bottomOfWindow = document.documentElement.scrollTop + window.innerHeight === document.documentElement.offsetHeight;
                    if (bottomOfWindow) {
                        if (this.travels.next_page_url) {
                            axios.get(this.travels.next_page_url)
                                .then((response) =>{
                                    response.data.data.forEach(function (data) {
                                        this.travels.data.push(data)
                                    }, this),
                                        this.travels.next_page_url = response.data.next_page_url
                                })
                        }
                    }
                };
            }
        }
    }
</script>
