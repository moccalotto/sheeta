<template>
    <div v-if="!!sheet">
        <h1 class="title">{{ sheet.headline }}</h1>

        <div class="columns is-multiline">

            <div v-for="table, tableIdx in sheet.tables" :class="classForTable(table)">
                <table class="table">
                    <caption class="title is-3" v-show="table.visible_headline">
                        {{ table.headline }}
                    </caption>
                    <colgroup>
                        <col v-for="col in table.columns" :style="styleForCol(col)">
                    </colgroup>
                    <thead v-show="table.visible_headline">
                        <th v-for="col in table.columns">
                            {{ col.headline }}
                        </th>
                    </thead>
                    <tbody>
                        <tr v-for="row,rowIdx in table.rows" :class="classForRow(tableIdx, rowIdx)" @click.prevent="toggleEdit(tableIdx,rowIdx)">
                            <td v-for="col,colIdx in row">
                                <span class="is-info">{{ col }}</span>
                            </td>
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
                edit: {
                    enabled: false,
                    tableIdx: null,
                    rowIdx: null,
                }
            };
        },
        created() {
            this.fetch();
        },
        methods: {
            toggleEdit(tableIdx, rowIdx) {
                if (this.isEditing(tableIdx, rowIdx)) {
                    return this.unedit();
                }
                this.edit.enabled = true;
                this.edit.tableIdx = tableIdx;
                this.edit.rowIdx = rowIdx;
            },
            unedit() {
                this.edit.enabled = false;
                this.tableIdx = null;
                this.rowIdx = null;
            },
            isEditing(tableIdx, rowIdx) {
                return this.edit.enabled == true
                    && this.edit.tableIdx == tableIdx
                    && this.edit.rowIdx == rowIdx;
            },
            classForRow(tableIdx, rowIdx) {
                return {
                    'is-selected': this.isEditing(tableIdx, rowIdx),
                };
            },
            styleForCol(col) {
                if (col.width || false) {
                    return {
                        width: (100 * col.width / 12) + '%'
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
