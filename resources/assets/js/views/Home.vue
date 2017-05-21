<template>
    <div>
        <div class="box">
            <div class="field has-addons">
                <p class="control is-expanded">
                    <input class="input" v-model="filter" @keyup.enter="fetchSearchResults" type="text" placeholder="Find a sheet">
                </p>
                <p class="control">
                    <a class="button is-info" @click="fetchSearchResults">
                        Search
                    </a>
                </p>
            </div>
        </div>
        <div v-for="sheet in sheets">
            <sheet-card :sheet="sheet"></sheet-card>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                filter: '',
                sheets: [],
                total: 0,
                per_page: 15,
                last_page: 1,
                next_page_url: null,
                prev_page_url: null,
                from: 1,
                to: 1,
            }
        },
        methods: {
            fetchSearchResults(ev) {
                axios.get('/api/sheets/', {
                    params: {
                        headline: this.filter
                    }
                }).then( ({data}) => {
                    this.sheets = data.data;
                    this.per_page = data.per_page;
                    this.last_page = data.last_page;
                    this.prev_page_url = data.prev_page_url;
                    this.next_page_url = data.next_page_url;
                    this.from = data.from;
                    this.to = data.to;
                    console.log(response);
                });
            }
        }
    }
</script>
