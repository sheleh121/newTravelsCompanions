<template>

        <div :class="input_group ? 'input-group' : ''">
            <select :class=" errors != null ? ('country' in errors ? 'form-control is-invalid' : 'form-control') : 'form-control' " name = "country" id = "country" @change="GetRegion"  v-model="selected.country" >
                <option v-if="form" :value="0">Страна*</option>
                <option v-else :value="0">Страна</option>
                <option v-for="option in countries" v-bind:value="option.id">
                    {{ option.name }}
                </option>

            </select>

            <select :class=" errors != null ? ('region' in errors ? 'form-control is-invalid' : 'form-control') : 'form-control' "
                    name = "region" id = "region" required @change="GetCities"  v-model="selected.region" v-if="regionSeen">
                <option v-if="form" :value="0">Регион*</option>
                <option v-else :value="0">Регион</option>
                <option v-for="option in region" v-bind:value="option.id">
                    {{ option.name }}
                </option>
            </select>

            <select :class=" errors != null ? ('city' in errors ? 'form-control is-invalid' : 'form-control') : 'form-control' "
                    name = "city" id = "city" @change="$emit('selectedlocation', selected)" v-model="selected.city" v-if="citiesSeen">
                <option v-if="form" :value="0">Город*</option>
                <option v-else :value="0">Город</option>
                <option v-for="option in cities" v-bind:value="option.id">
                    {{ option.name }}
                </option>
            </select>
        </div>

</template>

<script>
    export default {
        props: {
            old: {
                default: null
            },
            errors: {
                default: null
            },

            input_group: {
                default: true
            },

            form: {
                default: false
            }

        },
        data: function (){
            return{
                countries: Array,
                region: Array,
                cities: Array,

                selected: {
                    country: 0,
                    region: 0,
                    city: 0,
                },

                regionSeen: false,
                citiesSeen: false
            }
        },
        watch: {
            old: {
                handler: function() {
                    this.Init()
                },
                deep: true
            }
        },
        mounted() {
            this.Init()
        },
        methods: {
            Init: function (){
                if (this.$root.mobile) {
                    this.input_group = false;
                }
                this.GetCountries();

            },
            GetCountries: function () {
                axios.get('/api/location/countries').then((response) =>{
                    this.countries = response.data
                })
                this.$emit('selectedlocation', this.selected)

            },
            GetRegion: function () {
                axios.get('/api/location/region', {
                    params: {
                        country: this.selected.country,
                    }}).then((response) =>{
                    this.region =response.data
                    this.regionSeen = true
                    this.citiesSeen = false
                    this.selected.region = this.selected.city = 0

                    this.$emit('selectedlocation', this.selected)
                })
            },
            GetCities: function () {
                axios.get('/api/location/cities', {
                    params: {
                        region: this.selected.region
                    }}).then((response) =>{
                    this.cities =response.data
                    this.citiesSeen = true
                    this.selected.city = 0

                    this.$emit('selectedlocation', this.selected)
                })
            }
        }
    }
</script>
