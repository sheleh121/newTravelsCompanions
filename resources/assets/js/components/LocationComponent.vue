<template>

    <div :class="input_group ? 'input-group' : ''">

        <multiselect
            :class=" errors != null ? ('country' in errors ? ' is-invalid ' : '') : '' "
            v-model="selected.country"
            :options="countries"
            select-label=""
            deselect-label=""
            placeholder="страна"
            @input="GetRegion"
            track-by="id" label="name"
            style="margin-bottom: 5px">
        </multiselect>
        <multiselect
            v-if="regionSeen"
            :class=" errors != null ? ('region' in errors ? ' is-invalid ' : '') : '' "
            v-model="selected.region"
            :options="region"
            select-label=""
            deselect-label=""
            placeholder="регион"
            @input="GetCities"
            track-by="id" label="name"
            style="margin-bottom: 5px">
        </multiselect>
        <multiselect
            v-if="citiesSeen"
            :class=" errors != null ? ('city' in errors ? ' is-invalid ' : '') : '' "
            v-model="selected.city"
            :options="cities"
            select-label=""
            deselect-label=""
            placeholder="город"
            track-by="id" label="name"
            style="margin-bottom: 5px">
        </multiselect>
    </div>

</template>

<script>
    import Multiselect from 'vue-multiselect'
    export default {
        components: { Multiselect },
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
            },
            default_country: {
                default: true
            }

        },
        data: function (){
            return{
                countries: Array,
                region: Array,
                cities: Array,

                selected: {
                    country: null,
                    region: null,
                    city: null,
                },

                regionSeen: false,
                citiesSeen: false,

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
                this.GetRegion();

                if (this.default_country)
                    this.selected.country = {
                        id: 3159,
                        name: "Россия"
                    };

            },
            GetCountries: function () {
                axios.get('/api/location/countries').then((response) =>{
                    let result = [];
                    for(let i in response.data)
                        result.push(response.data[i]);
                    this.countries = result;
                });
                this.$emit('selectedlocation', this.selected)

            },
            GetRegion: function () {
                axios.get('/api/location/region', {
                    params: {
                        country: this.selected.country.id,
                    }}).then((response) =>{
                    let result = [];
                    for(let i in response.data)
                        result.push(response.data[i]);
                    this.region =result;

                    this.regionSeen = true
                    this.citiesSeen = false
                    this.selected.region = this.selected.city = null

                    this.$emit('selectedlocation', this.selected)
                })
            },
            GetCities: function () {
                axios.get('/api/location/cities', {
                    params: {
                        region: this.selected.region.id
                    }}).then((response) =>{
                    let result = [];
                    for(let i in response.data)
                        result.push(response.data[i]);
                    this.cities =result;

                    this.citiesSeen = true
                    this.selected.city = 0

                    this.$emit('selectedlocation', this.selected)
                })
            }
        }
    }
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
