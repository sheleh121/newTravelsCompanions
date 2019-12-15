<template>
    <div>

        <header id="header">

            <div class="container main-menu">
                <div class="row align-items-center justify-content-between d-flex">
                    <nav id="nav-menu-container">
                        <ul class="nav-menu">
                                    <li v-if="user != null"  >
                                        <router-link :to="{ name: 'messages' } " :style="$root.no_seen_messages > 0 ? 'color: #FF4F00' : '' ">СООБЩЕНИЯ</router-link>
                                    </li>
                                    <li v-if="user != null" >
                                        <router-link :to="{ name: 'user_travels' } " >МОИ СОБЫТИЯ</router-link>
                                    </li>
                                    <li >
                                        <router-link :to="{ name: 'travels' } "  >СОБЫТИЯ</router-link>
                                    </li>
                                    <li >
                                        <router-link :to="{ name: 'users' } " >ПОЛЬЗОВАТЕЛИ</router-link>
                                    </li>
                                    <li >
                                        <router-link :to="{ name: 'help' } " >ГДЕ Я?</router-link>
                                    </li>
                            <li v-if="user == null" class="float-right" >
                                <router-link to="/login">ВХОД</router-link>
                            </li>
                            <li v-else >
                                <router-link :to="{ name: 'user', params: { user_id: user.id  }}" >Моя страница</router-link>
                            </li>
                            <li v-if="authenticated && user" class="menu-has-children">

                                    <a  v-if="user !== null" @click="noticesSeen()" href="#" id="noticeDropdown"
                                        role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" :style="no_seen_notices === true ? 'color: #FF4F00' : '' ">
                                        УВЕДОМЛЕНИЯ
                                    </a>
                                <ul>
                                    <li v-if="notices.length > 0" v-for="notice in notices">
                                        <a href="#" @click="$router.push({ name: 'travel', params: { travel_id: notice.travel_id }})">
                                            {{'"' + notice.travel_name + '" ' +notice.description}}
                                        </a>

                                    </li>
                                    <li v-if="notices.length == 0">
                                        <a href="#">Нет новых уведомлений</a>
                                    </li>
                                </ul>

                            </li>


                        </ul>
                    </nav><!-- #nav-menu-container -->
                </div>
            </div>
        </header><!-- #header -->

        <!-- start banner Area -->

        <!-- start banner Area -->





        <div>
            <router-view></router-view>
        </div>





    </div>
</template>
<script>
    export default {
        data() {
            return {
                authenticated: auth.check(),
                user: auth.user,
                notices: [],
                no_seen_notices: false,
            };
        },
        mounted() {
            Event.$on('userLoggedIn', () => {
                this.authenticated = true;
                this.user = auth.user;
            });
            this.noticesInit();

        },
        methods: {

            logout() {
                auth.logout()
            },
            noticesInit() {
                if (auth.check() && auth.locked() === false) {
                    axios.get('/api/chat/messages/noseen').then((response)=>{
                        this.$root.no_seen_messages = response.data
                    });
                    axios.get('/api/notices')
                        .then((response) => {
                            response.data.forEach(function (notice) {
                                this.notices.push(notice);
                                if (this.notices.length > 0)
                                    this.no_seen_notices = true;
                            }, this)
                        })
                    window.Echo.private('notice.' + this.user.id)
                        .listen('Travels\\TravelNoticeEvent', (notice) => {
                            this.notices.unshift(notice);
                            this.no_seen_notices = true;
                        });
                    window.Echo.private('notice-message.' + this.user.id)
                        .listen('Chat\\RoomSubscribeEvent', () => {
                            this.$root.no_seen_messages += 1;
                        });
                }
            },
            noticesSeen () {
                this.notices.forEach(function (notice) {
                    axios.post('/api/notices/' + notice.id + '/seen')
                        .then (() => {
                        notice.seen = true;
                    })
                }, this)
                this.no_seen_notices = false
            }
        }
    }
</script>
