<template>
    <div style="margin-top: 200px;">
        {{ hello }}
    </div>
</template>

<script>
    import {mapState} from 'vuex'

    export default {
        computed: {
            hello(){
                return this.$store.state.header.hello;
            }
        }
    }

</script>
s
