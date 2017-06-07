<template>
    <div>
        <div class="box">
            <div class="field has-addons">
                <p class="control is-expanded">
                    <input class="input" v-model="typedFilter" @keyup.enter="changeRoute(typedFilter, 1)" type="text" placeholder="Find a sheet" autofocus>
                </p>
                <p class="control">
                    <a class="button is-info" @click.prevent="changeRoute(typedFilter, 1)">
                        Search
                    </a>
                </p>
            </div>
        </div>
        <template v-if="dataSet">
            <item-paginator v-if="dataSet.last_page > 1" :loading="loading" @pageChanged="pageChanged" :dataSet="dataSet"></item-paginator>
            <sheet-list :sheets="dataSet.data"></sheet-list>
            <item-paginator v-if="dataSet.last_page > 1" :loading="loading" @pageChanged="pageChanged" :dataSet="dataSet"></item-paginator>
        </template>
        <pulse-loader v-if="!dataSet"></pulse-loader>
    </div>
</template>

<script>
    export default {
        components: {
            'sheet-list': require('../components/SheetList'),
        },
        data() {
            return {
                loading:        false,
                dataSet:        null,
                typedFilter:    this.$route.query.filter || '',
            }
        },
        computed: {
            currentPage() {
                return parseInt(this.$route.query.page || 1);
            },
            currentFilter() {
                return this.$route.query.filter || '';
            },
        },
        watch: {
            '$route': 'fetchSheets',
        },
        created() {
            this.fetchSheets();
            window.eventBus.$on('auth.login', () => { this.fetchSheets() });
            window.eventBus.$on('auth.logout', () => { this.fetchSheets() });
        },
        methods: {
            changeRoute(newFilter, newPage) {
                this.$router.push({
                    path: '/',
                    query: {
                        filter: newFilter,
                        page: newPage,
                    },
                });
            },
            pageChanged(newPage) {
                this.changeRoute(this.currentFilter, newPage);
            },
            fetchSheets() {
                if (this.loading) {
                    return;
                }
                this.loading = this.currentPage;
                this.typedFilter = this.currentFilter;

                axios.get('/api/sheets/', {
                    params: {
                        headline: this.currentFilter,
                        page: this.currentPage,

                    }
                }).then( ({data}) => {
                    this.dataSet = data;
                    this.loading = null;
                });
            }
        },
    }
</script>
