<template>
    <div class="sheet-list">
        <item-paginator v-if="dataSet.last_page > 1" :loading="loading" :dataSet="dataSet" @pageChanged="fetch"></item-paginator>
        <div v-for="sheet in sheets" :key="sheet.id">
            <sheet-card :sheet="sheet"></sheet-card>
        </div>
        <item-paginator v-if="dataSet.last_page > 1" :loading="loading" :dataSet="dataSet" @pageChanged="fetch"></item-paginator>
    </div>
</template>

<script>
    export default {
        props: ['filter', 'page'],
        data() {
            return {
                sheets: [],
                dataSet: {},
                currentPage: null,
                loading: null,
            }
        },
        created() {
            this.fetch(this.page || 1);
        },
        watch: {
            filter() {
                this.fetch(1);
            },
            page() {
                this.fetch(this.page);
            },
        },
        methods: {
            fetch(page) {
                this.loading = page;
                axios.get('/api/sheets/', {
                    params: {
                        headline: this.filter,
                        page

                    }
                }).then( ({data}) => {
                    this.sheets = data.data;
                    this.dataSet = data;
                    this.loading = null;
                    this.currentPage = data.current_page;
                    this.$emit('pageChanged', this.currentPage);
                });
            }
        },
    }
</script>
