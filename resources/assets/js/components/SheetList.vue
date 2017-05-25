<template>
    <div class="sheet-list">
        <div v-for="sheet in sheets" :key="sheet.id">
            <sheet-card :sheet="sheet"></sheet-card>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            sheets: {
                type: Array,
                required: true,
            },
        },
    }
</script>
