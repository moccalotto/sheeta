<template>
    <td>
        <div :class="outerClass">
            <input v-if="canEdit"
            :type="inputType"
            :value="value"
            :min="cell.min_value"
            :max="cell.max_value"
            :pattern="cell.validation"
            :caption="cell.headline"
            @input="$emit('changed', $event)"
            :class="inputClass">
            <div v-else :class="textClass">
                {{ value }}
            </div>
        </div>
    </td>
</template>
<script>
    export default {
        props: ['cell', 'value', 'editMode'],
        computed: {
            canEdit() {
                if (this.editMode == 'force') {
                    return true;
                }
                if (this.editMode == 'cell') {
                    return !! this.cell.is_edit;
                }

                return false;
            },
            outerClass() {
                return {
                    'strong': !! this.cell.is_header,
                    'field': !! this.canEdit,
                }
            },
            inputClass() {
                return {
                    'input': true,
                    'is-large': !! this.cell.is_huge,
                    'is-small': ! (this.cell.is_large || this.cell.is_huge),
                };
            },
            textClass() {
                return {
                    'is-large': !! this.cell.is_large,
                };
            },
            inputType() {
                return this.cell.format === 'number' ? 'number' : 'text';
            },
        },
    }
</script>
<style>
.strong {
    font-weight: bold;
}
</style>
