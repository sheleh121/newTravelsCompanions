<template>
    <div @click="getTravel(travel.id)" class="col-lg-4" style="cursor: pointer;" >

        <div class="single-destinations">
            <div class="details">
                <h4>{{ travel.name}}</h4>
                <p>
                    {{travel.country.name + ' ' + travel.region.name + ' ' + travel.city.name }}
                    с  {{travel.date_begin}} по {{travel.date_end}}
                </p>
                <ul class="package-list">
                    <li class="d-flex justify-content-between align-items-center">
                        <span>Тип: </span>
                        <span>{{travel.type.name}}</span>
                    </li>
                    <li class="d-flex justify-content-between align-items-center">
                        <span>Категория: </span>
                        <span>{{travel.category.name}}</span>
                    </li>
                    <li class="d-flex justify-content-between align-items-center">
                        <span>Автор: </span>
                        <span>{{travel.author.surname + ' ' + travel.author.name + ' ' + travel.author.patronymic}}</span>
                    </li>
                    <span class="text-break">{{travel.description | capitalize(80, '...')}}</span>
                </ul>

            </div>

        </div>
    </div>
</template>

<script>
    export default {
        props: {
            travel: Object,
            claim: 0
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
            getTravel: function (travelId) {
                this.$router.push('/travels/' + travelId)
            },
        }

    }

</script>

