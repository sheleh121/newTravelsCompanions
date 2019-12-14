<template>
        <section  class="banner-area relative" >
            <div class="overlay overlay-bg"></div>
            <div class="container">
                <div class="row align-items-center justify-content-between" :style="'height: ' + $root.windowHeight + 'px'">
                    <div class="col-lg-3 col-md-3 banner-left" >

                    </div>

                    <div class="col-lg-8 col-md-8 banner-right">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="login-tab" data-toggle="tab" href="#login" role="tab" aria-controls="flight" aria-selected="true">Войти</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="reg-tab" data-toggle="tab" href="#reg" role="tab" aria-controls="hotel" aria-selected="false">Зарегистрироваться</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="forgot-tab" data-toggle="tab" href="#forgot" role="tab" aria-controls="hotel" aria-selected="false">Забыли пароль?</a>
                            </li>

                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="login" role="tabpanel" aria-labelledby="login-tab">
                                <div class="form-wrap">
                                    <div v-if="error !== null" class="alert alert-danger alert-dismissible" role="alert">
                                        {{ error }}
                                        <button type="button" class="close" @click="error = null" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="input-group " style="margin-bottom: 10px">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">+7</span>
                                        </div>
                                        <input type="tel"
                                               v-model="username"
                                               name="phone"
                                               id="phone"
                                               placeholder="(XXX) XXX-XX-XX"
                                               autocomplete="tel"
                                               maxlength="15"
                                               class="form-control input-group"
                                               style="margin-bottom: 0"
                                               v-phone
                                               pattern="[(][0-9]{3}[)] [0-9]{3}-[0-9]{4}" required />

                                    </div>
                                    <input v-model="password"  id="password" type="password" class="form-control" name="password" required>
                                    <button class="primary-btn text-uppercase" @click="login()" >ВОЙТИ</button>
                                </div>
                            </div>
                            <div class="tab-pane fade " id="reg" role="tabpanel" aria-labelledby="reg-tab">
                                <register-component/>
                            </div>
                            <div class="tab-pane fade " id="forgot" role="tabpanel" aria-labelledby="forgot-tab">
                                <forgot-component/>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </section>

</template>

<script>
    export default {
        data() {
            return {
                username: '',
                password: '',
                error: null,
                activeTab: 0
            };
        },

        methods: {
            login() {
                let data = {
                    username: this.username,
                    password: this.password
                };

                axios.post('/api/login', data)
                    .then(({data}) => {
                        auth.login(data.token, data.user);

                        this.$router.push('/');
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.error = error.response.data;
                        }
                    });
            }
        }
    }
</script>

<style scoped>

</style>
