<template>
    <span class="moment" :title="hint">{{ content}}</span>
</template>

<script>
    export default {
        props: ['date'],
        data() {
            return {
                now: new moment(this.date + 'Z')
            };
        },
        created() {
            setInterval( () => {
                this.now = new moment(this.date + 'Z');
            }, 5000);
        },
        computed: {
            content() {
                return this.now.fromNow();
            },
            hint() {
                return this.now.calendar();
            }
        }
    }
</script>
