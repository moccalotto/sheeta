<template>
    <div v-if="!!sheet">
        <h1 class="title">{{ sheet.headline }}</h1>

        <div class="columns is-multiline">
            <div v-for="table in sheet.tables" :class="classForTable(table)">
                <table class="table">
                    <caption v-if="table.visible_headline">{{ table.headline }}</caption>
                    <colgroup>
                        <col v-for="col in table.columns" :style="styleForCol(col)">
                    </colgroup>
                    <thead>
                        <th v-for="col in table.columns">
                            {{ col.headline }}
                        </th>
                    </thead>
                    <tbody>
                        <tr v-for="row in table.rows">
                            <td v-for="col in row">{{ col }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['id', 'slug'],
        data() {
            return {
                sheet: null,
                loading: true,
            };
        },
        created() {
            this.fetch();
        },
        methods: {
            styleForCol(col) {
                if (col.width || false) {
                    return {
                        width: (col.width / 12) + '%'
                    }
                }
            },
            classForTable(table) {
                let res = { column: true, };

                if (table.width || false) {
                    res[`is-${table.width}`] = true
                } else {
                }

                return res;
            },
            fetch() {
                this.loading = true;
                axios.get('/api/sheets/' + this.id).then( ({data}) => {
                    this.sheet = data;
                    this.loading = false;
                }).catch ( (error) => {
                    console.log(error);
                });
            }
        },
    }
</script>
