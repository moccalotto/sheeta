<template>
    <div>
        <div class="box">
            <div class="field has-addons">
                <p class="control is-expanded">
                    <input class="input" v-model="typedFilter" @keyup.enter="applyFilter($event.target.value)" type="text" placeholder="Find a sheet" autofocus>
                </p>
                <p class="control">
                    <a class="button is-info" @click.prevent="applyFilter(typedFilter)">
                        Search
                    </a>
                </p>
            </div>
        </div>
        <sheet-list :filter="appliedFilter" :page="currentPage" @pageChanged="pageChanged"></sheet-list>
    </div>
</template>

<script>
    export default {
        methods: {
            updateRoute() {
                this.$router.push({
                    path: '/',
                    query: {
                        filter: this.appliedFilter,
                        page: this.currentPage,
                    },
                });
            },
            applyFilter(value) {
                this.currentPage = 1;
                this.appliedFilter = value;
                this.updateRoute();
            },
            pageChanged(newPage) {
                this.currentPage = newPage;
                this.updateRoute();
            },
            routeChanged(route) {
                this.typedFilter = '' + (route.query.filter || '');
                this.appliedFilter = '' + (route.query.filter || '');
                this.currentPage = '' + (route.query.page || 1);
            },
        },
        data() {
            return {
                currentPage:    this.$route.query.page || 1,
                appliedFilter:  this.$route.query.filter || '',
                typedFilter:    this.$route.query.filter || '',
            }
        },
        watch: {
            '$route': 'routeChanged',
        },
    }
</script>
