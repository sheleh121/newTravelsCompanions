<template>
    <div class="form-wrap">
        <input type="text" :class="'form-control' + ('surname' in errors ? ' is-invalid' : '')"
               placeholder="Фамилия*" id="surname" name="surname"  class="form-control" v-model="user.surname" >
        <input type="text" :class="'form-control' + ('name' in errors ? ' is-invalid' : '')"
               placeholder="Имя*" id="name" name="name" v-model="user.name" >
        <input type="text" :class="'form-control' + ('patronymic' in errors ? ' is-invalid' : '')"
               placeholder="Отчество*" id="patronymic" name="patronymic" v-model="user.patronymic" >
        <div class="input-group"  style="margin-bottom: 10px">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">+7</span>
            </div>
            <input v-model="user.phone"
                   type="tel"
                   name="phone"
                   id="phone"
                   placeholder="(XXX) XXX-XX-XX"
                   autocomplete="tel"
                   maxlength="15"
                   :class="'form-control' + ('phone' in errors ? ' is-invalid' : '')"
                   style="margin-bottom: 0"
                   v-phone
                   pattern="[(][0-9]{3}[)] [0-9]{3}-[0-9]{4}" required />

        </div>


        <location-component @selectedlocation = 'selectedLocation' v-bind:errors = "errors" :form = "true" />
        <input v-model="user.password" id="password" name="password" type="password" :class="'form-control' + ('password' in errors ? ' is-invalid' : '')" placeholder="Пароль*" >
        <input v-model="user.password_confirm" id="password_confirm" name="password_confirmation" type="password" :class="'form-control' + ('password' in errors ? ' is-invalid' : '')" placeholder="Подтверждение пароля*" >
        <button @click="create()" type="submit" class="primary-btn text-uppercase">Зарегистрироваться</button>
    </div>
</template>

<script>
    export default {
        data: function (){
            return{

                user: {
                    name: '',
                    surname: '',
                    patronymic: '',
                    country: 0,
                    region: 0,
                    city: 0,
                    email: '',
                    phone: '',
                    password: '',
                    password_confirm: '',

                },
                errors: [],
                image: null
            }
        },
        mounted() {
        },
        methods: {
            selectedLocation: function (location) {
                this.selected.country_id = (location.country === null ? 0 : location.country.id);
                this.selected.region_id = location.region === null ? 0 : location.region.id;
                this.selected.city_id = location.city === null ? 0 : location.city.id;
            },
            nameImage(event) {
                this.image = event.target.files[0].name;
            },
            create: function () {
                this.errors = null;
                var imagefile = document.querySelector('#image');
                let form = new FormData();
                form.append('surname', this.user.surname);
                form.append('name', this.user.name);
                form.append('patronymic', this.user.patronymic);
                //form.append('image', imagefile.files[0]);
                form.append('country', this.user.country);
                form.append('region', this.user.region);
                form.append('city', this.user.city);
                form.append('email', this.user.email);
                form.append('phone', this.user.phone);
                form.append('password', this.user.password);
                form.append('password_confirmation', this.user.password_confirm);

                axios
                    .post('/api/register', form)
                    .then(({data}) => {
                        auth.login(data.token, data.user);
                        this.$router.push('confirm');
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.errors = error.response.data.errors;
                        }
                    });
            }
        }
    }
</script>
