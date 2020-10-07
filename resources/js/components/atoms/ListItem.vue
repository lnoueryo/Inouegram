        <v-list three-line v-if="thisImageComments.length > 0">
            <v-list-item color="pink" active-class="text--pink">

                <v-list-item-content style="align-items: start; ">
                    <v-list-item-subtitle style="-webkit-line-clamp: initial;"><span class='text--primary'>{{ userName(thisImageComment.user_id) }}</span> <br>{{ thisImageComment.text }}</v-list-item-subtitle>
                </v-list-item-content>
            </v-list-item>
        </v-list>