<template>
    <div class="sheet-list">
        <item-paginator v-if="dataSet.last_page > 1" :dataSet="dataSet" @pageChanged="pageChanged"></item-paginator>
        <div v-for="sheet in sheets" :key="sheet.id">
            <sheet-card :sheet="sheet"></sheet-card>
        </div>
        <item-paginator v-if="dataSet.last_page > 1" :dataSet="dataSet" @pageChanged="pageChanged"></item-paginator>
    </div>
</template>

<script>
    export default {
        props: ['filter'],
        data() {
            return {
                sheets: [],
                dataSet: {},
                currentPage: 1,
            }
        },
        created() {
            this.fetch();
        },
        watch: {
            filter() {
                this.currentPage = 1;
                this.fetch();
            }
        },
        methods: {
            pageChanged(page) {
                this.currentPage = page;
                this.$emit('pageChanged', this.currentPage);
                this.fetch();
            },
            fetch() {
                axios.get('/api/sheets/', {
                    params: {
                        headline: this.filter,
                        page: this.currentPage,

                    }
                }).then( ({data}) => {
                    this.sheets = data.data;
                    this.dataSet = data;
                });
            }
        },
    }
</script>
