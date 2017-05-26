<template>
    <div v-if="!!sheet" class="menu-parent">
        <context-menu :event="edit.event" heading="Edit Cell">
            <a class="panel-block is-active">
                <span class="panel-icon">
                    <i class="fa fa-book"></i>
                </span>
                bulma
            </a>
            <a class="panel-block">
                <span class="panel-icon">
                    <i class="fa fa-book"></i>
                </span>
                marksheet
            </a>
        </context-menu>
        <h1 class="title is-spaced">
            {{ sheet.headline }}
            <router-link :to="{ name: 'editSheet', params: { id: $route.params.id }}">
                <span class="icon">
                    <i class="fa fa-pencil"></i>
                </span>
            </router-link>
        </h1>

        <div class="columns is-multiline">

            <div v-for="table, tableIdx in sheet.tables" :class="classForColumn(table)">
                <table class="table">
                    <caption class="title is-3" v-show="table.visible_headline">
                        {{ table.headline }}
                    </caption>
                    <colgroup>
                        <col v-for="col in table.columns" :style="styleForCol(col)">
                    </colgroup>
                    <thead v-show="table.visible_headers">
                        <th v-for="col in table.columns">
                            {{ col.headline }}
                        </th>
                    </thead>
                    <tbody>
                        <tr v-for="row,rowIdx in table.rows">
                            <template v-for="col,colIdx in row">
                                <th v-if="table.columns[colIdx].is_header">
                                    <div :class="classForCell(tableIdx, rowIdx, colIdx)" @click.prevent.stop="toggleEdit(tableIdx,rowIdx,colIdx,$event)">
                                        {{ col }}
                                    </div>
                                </th>
                                <td v-else>
                                    <div :class="classForCell(tableIdx, rowIdx, colIdx)" @click.prevent.stop="toggleEdit(tableIdx,rowIdx,colIdx,$event)">
                                        {{ col }}
                                    </div>
                                </td>
                            </template>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <pulse-loader v-else></pulse-loader>

</template>

<script>
    export default {
        props: ['id', 'slug'],
        data() {
            return {
                sheet: null,
                loading: true,
                edit: {
                    event: null,
                    tableIdx: null,
                    rowIdx: null,
                    colIdx: null,
                }
            };
        },
        created() {
            this.fetch();
            document.addEventListener('click', () => {
                this.unedit();
            });
        },
        methods: {
            toggleEdit(tableIdx, rowIdx, colIdx, event) {
                if (this.isEditing(tableIdx, rowIdx, colIdx)) {
                    // return this.unedit();
                }
                this.edit.event = event;
                this.edit.tableIdx = tableIdx;
                this.edit.rowIdx = rowIdx;
                this.edit.colIdx = colIdx;
            },
            unedit() {
                this.edit = {
                    event: null,
                    tableIdx: null,
                    rowIdx: null,
                    colIdx: null,
                };
            },
            isEditing(tableIdx, rowIdx, colIdx) {
                return this.edit.event != null
                    && this.edit.tableIdx == tableIdx
                    && this.edit.rowIdx == rowIdx
                    && this.edit.colIdx == colIdx;
            },
            classForCell(tableIdx, rowIdx, colIdx) {
                return {
                    'is-selected': this.isEditing(tableIdx, rowIdx, colIdx),
                };
            },
            styleForCol(col) {
                if (col.width || false) {
                    return {
                        width: (100 * col.width / 12) + '%'
                    }
                }
            },
            classForColumn(table) {
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
                    switch (error.response.status) {
                        case 401:
                            flash('danger', 'You are not allowed to see this sheet!', 3000);
                            break;
                        case 403:
                            flash('danger', 'You need to be signed in to see this sheet.', 3000);
                            break;
                        default:
                            flash('danger', `You cannot see this sheet: ${error.message}`, 3000);
                            break;
                    }
                });
            }
        },
    }
</script>
