<template>
    <div>
        <section class="about-banner relative">
            <div class="overlay overlay-bg"></div>
            <div class="container">
                <div class="row d-flex align-items-center justify-content-center">
                    <div class="about-content col-lg-12">
                        <h1 class="text-white">
                            Уведомления
                        </h1>
                    </div>
                </div>
            </div>
        </section>
        <div class="container">
            <button v-if="notices.length > 0" v-for="notice in notices" @click="$router.push({ name: 'travel', params: { travel_id: notice.travel_id }})" class="btn btn-block btn-light main-wrap " >
                {{'"' + notice.travel_name + '" ' +notice.description}}
            </button>
            <div v-if="notices.length == 0" class="main-wrap " >
                Нет новых уведомлений
            </div>
        </div>
    </div>

</template>

<script>
    export default {
        name: "Notices",
        props: {
            notices: []
        }

    }
</script>

<style scoped>

</style>
