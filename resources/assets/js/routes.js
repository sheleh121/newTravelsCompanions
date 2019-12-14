import VueRouter from 'vue-router';
let routes = [

    {
        path: '/help',
        name: 'help',
        component: require('./views/Help')
    },

    {
        path: '/',
        name: 'home',
        component: require('./views/travels/Travels')
    },

    {
        path: '/travels',
        name: 'travels',
        component: require('./views/travels/Travels'),
    },

    {
        path: '/my-travels',
        name: 'user_travels',
        component: require('./views/users/Travels'),
        meta: { middlewareAuth: true}
    },

    {
        path: '/travels/new',
        name: 'travel_new',
        component: require('./views/travels/New'),
        meta: { middlewareAuth: true}
    },

    {
        path: '/travels/:travel_id',
        name: 'travel',
        component: require('./views/travels/Travel'),
        props: true,
    },
    {
        path: '/travels/:travel_id/edit',
        name: 'travel_edit',
        component: require('./views/travels/Edit'),
        props: true,
        meta: { middlewareAuth: true}
    },
    {
        path: '/travels/:travel_id/edit/images',
        name: 'travel_edit_images',
        component: require('./views/travels/EditImages'),
        props: true,
        meta: { middlewareAuth: true}
    },


    {
        path: '/messages',
        name: 'messages',
        component: require('./views/users/Rooms'),
        meta: { middlewareAuth: true}
    },



    {
        path: '/users',
        name: 'users',
        component: require('./views/users/Users.vue'),
        meta: { middlewareAuth: true}
    },

    {
        path: '/users/:user_id',
        name: 'user',
        component: require('./views/users/User.vue'),
        props: true,
        meta: { middlewareAuthNoCheckLocked: true}
    },

    {
        path: '/notices/',
        name: 'notices',
        component: require('./views/users/Notices.vue'),
        props: true,
        meta: { middlewareAuth: true}
    },

    {
        path: '/user/edit',
        name: 'user_edit',
        component: require('./views/users/Edit.vue'),
        props: true,
        meta: { middlewareAuthNoCheckLocked: true}
    },

    {
        path: '/login',
        name: 'login',
        component: require('./views/auth/Login.vue')
    },

    {
        path: '/confirm',
        name: 'confirm',
        component: require('./views/auth/Confirm.vue'),
        meta: { middlewareAuthNoCheckLocked: true}
    },


];

const router = new VueRouter({
    mode: 'history',
    routes
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.middlewareAuth)) {
        if (!auth.check()) {
            next({
                path: '/login',
                query: { redirect: to.fullPath }
            });

            return;
        }
        else if (auth.locked()) {
            next({
                path: '/confirm',
                query: { redirect: to.fullPath }
            });

            return;
        }
    }
    if (to.matched.some(record => record.meta.middlewareAuthNoCheckLocked)) {
        if (!auth.check()) {
            next({
                path: '/login',
                query: {redirect: to.fullPath}
            });
        }
    }

    next();
});



export default router;

