<template>
    <div>

        <header id="header">

            <div class="container main-menu ">
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

            $(window).scroll(function() {
                if ($(this).scrollTop() > 100) {
                    $('#header').addClass('header-scrolled');
                } else {
                    $('#header').removeClass('header-scrolled');
                }
            });

            if ($('#nav-menu-container').length) {
                var $mobile_nav = $('#nav-menu-container').clone().prop({
                    id: 'mobile-nav'
                });
                $mobile_nav.find('> ul').attr({
                    'class': '',
                    'id': ''
                });
                $('body .main-menu').append($mobile_nav);
                $('body .main-menu').prepend('<button type="button" id="mobile-nav-toggle"><i class="lnr lnr-menu"></i></button>');
                $('body .main-menu').append('<div id="mobile-body-overly"></div>');
                $('#mobile-nav').find('.menu-has-children').prepend('<i class="lnr lnr-chevron-down"></i>');

                $(document).on('click', '.menu-has-children i', function(e) {
                    $(this).next().toggleClass('menu-item-active');
                    $(this).nextAll('ul').eq(0).slideToggle();
                    $(this).toggleClass("lnr-chevron-up lnr-chevron-down");
                });

                $(document).on('click', '#mobile-nav-toggle', function(e) {
                    $('body').toggleClass('mobile-nav-active');
                    $('#mobile-nav-toggle i').toggleClass('lnr-cross lnr-menu');
                    $('#mobile-body-overly').toggle();
                });

                $(document).on('click', function(e) {
                    var container = $("#mobile-nav, #mobile-nav-toggle");
                    if (!container.is(e.target) && container.has(e.target).length === 0) {
                        if ($('body').hasClass('mobile-nav-active')) {
                            $('body').removeClass('mobile-nav-active');
                            $('#mobile-nav-toggle i').toggleClass('lnr-cross lnr-menu');
                            $('#mobile-body-overly').fadeOut();
                        }
                    }
                });
            } else if ($("#mobile-nav, #mobile-nav-toggle").length) {
                $("#mobile-nav, #mobile-nav-toggle").hide();
            }

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
                if (this.no_seen_notices) {
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
    }
</script>
