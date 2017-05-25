<template>
    <div class="flash-list">
        <flash-message :message="msgObj.message" :type="msgObj.type" v-for="msgObj,idx in messages" :key="idx"></flash-message>
    </div>
</template>
<style lang="sass">
.flash-list
    position: fixed
    bottom: 0
    right: 0
    padding: 1em
    width: 20em
</style>

<script>
    import FlashMessage from './FlashMessage';
    export default {
        components: { 'flash-message' : FlashMessage },
        data() {
            return {
                messages: [],
            };
        },
        created() {
            window.eventBus.$on('flash', ({ type, message, timeout }) => {
                timeout = parseInt(timeout) || 1000;

                this.messages.push({ type, message, timeout });

                setTimeout( () => {
                    this.messages.splice(0, 1);
                }, timeout);
            });

            // setTimeout - delete the message
        },
    }
</script>
