<template>
    <span class="moment" :title="hint">{{ content}}</span>
</template>

<script>
    export default {
        props: ['date'],
        computed: {
            content() {
                return moment(this.date).fromNow();
            },
            hint() {
                return moment(this.date).calendar();
            }
        }
    }
</script>
