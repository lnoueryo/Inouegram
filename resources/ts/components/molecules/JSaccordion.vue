<template>
    <div class="js-accordion" v-cloak>
        <button class="js-accordion--trigger" @click="accordionToggle()">
            <slot name="title"></slot>
        </button>
        <transition name="js-accordion" @before-enter="beforeEnter" @enter="enter" @before-leave="beforeLeave" @leave="leave">
            <div class="js-accordion--target" :class="{ '_state-open': isOpened }" v-if="isOpened">
            <slot name="body"></slot>
            </div>
        </transition>
    </div>
</template>

<script>
export default {
    data() {
        return {
        isOpened: false
        };
    },
    methods: {
        accordionToggle: function(){
            this.isOpened = !this.isOpened;
        },
        beforeEnter: function(el) {
            el.style.height = '0';
        },
        enter: function(el) {
            el.style.height = el.scrollHeight + 'px';
        },
        beforeLeave: function(el) {
            el.style.height = el.scrollHeight + 'px';
        },
        leave: function(el) {
            el.style.height = '0';
        }
    }
}
</script>

<style lang="scss">
.js-accordion{
  background-color: #fff;
  border-color: #666;
  border-width:1px 1px 0 1px;
  border-radius: 2px;
  &:last-child{
    border-bottom-width:1px;
  }
  &--trigger{

    &._state-open{
      background-color: #f1f1f1;
      text-decoration: none;
      &:after{
        transform:rotateX(180deg);
        margin-top: -10px;
      }
    }
    &:hover{
      background-color: #f1f1f1;
      text-decoration: none;
    }
  }
  &--target{
    overflow: hidden;
    transition: height 0.4s ease-in-out;
  }
  &--body{

    padding: 30px;
  }
  &-enter-active{
    animation-duration: 1s;
    animation-fill-mode: both;
    animation-name: js-accordion--anime__opend;
  }
  &-leave-active{
    animation-duration: 1s;
    animation-fill-mode: both;
    animation-name: js-accordion--anime__closed;
  }
}

@keyframes js-accordion--anime__opend {
  0% {
    opacity: 0;
  }
  100% {
    opacity: 1;
   }
}
@keyframes js-accordion--anime__closed {
  0% {
   opacity: 1;
  }

  100% {
    opacity: 0;
  }
}
</style>
