<template functional>
    <div>
        <v-list subheader style="max-height: 180px; overflow-y: scroll;">
        <v-subheader>いいね</v-subheader>
            <div>
                <v-list-item v-for="(like, index) in props.likes" :key="index" :href="'/profile?id=' + like.user.id">
                    <v-list-item-avatar>
                    <v-img :src="'storage/image/avatar/' + like.user.profile_image"></v-img>
                    </v-list-item-avatar>

                    <v-list-item-content>
                    <v-list-item-title v-text="like.user.screen_name"></v-list-item-title>
                    </v-list-item-content>

                    <v-list-item-icon>
                        <div v-if="like.reaction == 0"><v-icon color="yellow">mdi-emoticon</v-icon></div>
                        <div v-else-if="like.reaction == 1"><v-icon color="blue">mdi-emoticon-cry</v-icon></div>
                        <div v-else-if="like.reaction == 2"><v-icon color="orange">mdi-emoticon-lol</v-icon></div>
                        <div v-else-if="like.reaction == 3"><v-icon color="red">mdi-emoticon-angry</v-icon></div>
                        <div v-else><v-icon color="pink">mdi-emoticon-kiss</v-icon></div>
                    </v-list-item-icon>
                </v-list-item>
            </div>
        </v-list>
    </div>
</template>

<script>
export default {
    props: ['likes']
}
</script>