<template>
    <td>
        <div :class="outerClass">
            <input v-if="cell.is_edit"
            :type="inputType"
            :value="displayValue"
            :min="cell.min_value"
            :max="cell.max_value"
            :pattern="cell.validation"
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
        props: ['cell', 'value'],
        data() {
            return {
                displayValue: this.value,
            };
        },
        computed: {
            outerClass() {
                return {
                    'strong': !! this.cell.is_header,
                    'field': !! this.cell.is_edit,
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
