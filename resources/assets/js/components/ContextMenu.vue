<template>
    <div :style="menuStyle" class="context-menu" ref="menu">
        <nav class="panel">
            <p class="panel-heading" v-if="heading">
                {{ heading }}
            </p>
            <slot>
            </slot>
        </nav>
    </div>
</template>

<script>

export default {
    props: ['event', 'heading'],
    methods: {
        absPos(element) {
            let top = 0, left = 0;
            let currentElement = element;
            do {
                top += currentElement.offsetTop  || 0;
                left += currentElement.offsetLeft || 0;
                currentElement = currentElement.offsetParent;
            } while (currentElement);

            return { top, left }
        }
    },
    computed: {
        menuStyle() {
            if (!(this.event && this.event.target)) {
                return {left: 0, top: 0, zIndex: -1};
            }

            // TODO: place the menu below the target element.
            let elPos = this.absPos(this.event.target);

            return {
                left: `${elPos.left}px`,
                top: `${elPos.top + this.event.target.offsetHeight}px`,
            };
        },
    },
}
</script>

<style lang="sass">
.context-menu
    position: fixed;
    background-color: #fff
    z-index: 999
</style>
