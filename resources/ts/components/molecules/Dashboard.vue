<template>
    <div v-resize="onResize">
        <v-container fluid>
            <v-data-iterator :items="items" :items-per-page.sync="itemsPerPage" :page="page" :search="search" :sort-by="sortBy.toLowerCase()" :sort-desc="sortDesc" hide-default-footer>
            <template v-slot:header>
                <v-toolbar dark color="blue darken-3" class="mb-1">
                <v-text-field v-model="search" clearable flat solo-inverted hide-details prepend-inner-icon="mdi-magnify" label="Search"></v-text-field>
                <template v-if="$vuetify.breakpoint.mdAndUp">
                    <v-spacer></v-spacer>
                    <v-select v-model="sortBy" flat solo-inverted hide-details :items="keys" prepend-inner-icon="mdi-magnify" label="Sort by"></v-select>
                    <v-spacer></v-spacer>
                    <v-btn-toggle v-model="sortDesc" mandatory>
                    <v-btn large depressed color="blue" :value="false">
                        <v-icon>mdi-arrow-up</v-icon>
                    </v-btn>
                    <v-btn large depressed color="blue" :value="true">
                        <v-icon>mdi-arrow-down</v-icon>
                    </v-btn>
                    </v-btn-toggle>
                    <v-spacer></v-spacer>
                    <v-btn @click="userCreate">
                        create<v-icon right>mdi-plus</v-icon>
                    </v-btn>
                </template>
                </v-toolbar>
            </template>

            <template v-slot:default="props">
                <v-row>
                    <v-col v-for="item in props.items" :key="item.name" cols="12" sm="4" md="4" lg="4">
                        <v-card @click="openDialog(item.id)">
                            <v-card-title class="subheading font-weight-bold">
                                <v-avatar size="36" class="mr-2">
                                    <v-img :src="'/storage/image/avatar/' + item.profile_image"></v-img>
                                </v-avatar>
                                {{ item.id }}
                                {{ item.name }}
                                <v-spacer></v-spacer>
                                投稿{{ item.posts_count }}
                            </v-card-title>

                            <v-divider></v-divider>
                            <v-list dense>
                                <template v-if="item.bg_image">
                                    <v-img :src="'/storage/image/background/' + item.bg_image" max-height="150">
                                        <v-list-item v-for="(key, index) in filteredKeys" :key="index" style="background-color: #ffffff70;">
                                        <v-list-item-content :class="{ 'blue--text': sortBy === key }">
                                            {{ key }}:　{{ item[key.toLowerCase()] }}
                                        </v-list-item-content>
                                        </v-list-item>
                                    </v-img>
                                </template>
                                <template v-else>
                                    <v-img src="/storage/image/background/bg-1.jpg" max-height="150">
                                        <v-list-item v-for="(key, index) in filteredKeys" :key="index" style="background-color: #ffffff70;">
                                        <v-list-item-content :class="{ 'blue--text': sortBy === key }">
                                            {{ key }}:　{{ item[key.toLowerCase()] }}
                                        </v-list-item-content>
                                        </v-list-item>
                                    </v-img>
                                </template>
                            </v-list>
                        </v-card>
                    </v-col>
                </v-row>
            </template>

            <template v-slot:footer>
                <v-row class="mt-2" align="center" justify="center">
                <span class="grey--text">Items per page</span>
                <v-menu offset-y>
                    <template v-slot:activator="{ on, attrs }">
                    <v-btn dark text color="primary" class="ml-2" v-bind="attrs" v-on="on">
                        {{ itemsPerPage }}
                        <v-icon>mdi-chevron-down</v-icon>
                    </v-btn>
                    </template>
                    <v-list>
                    <v-list-item v-for="(number, index) in itemsPerPageArray" :key="index" @click="updateItemsPerPage(number)">
                        <v-list-item-title>{{ number }}</v-list-item-title>
                    </v-list-item>
                    </v-list>
                </v-menu>

                <v-spacer></v-spacer>

                <span class="mr-4 grey--text">
                    Page {{ page }} of {{ numberOfPages }}
                </span>
                <v-btn fab dark color="blue darken-3" class="mr-1" @click="formerPage">
                    <v-icon>mdi-chevron-left</v-icon>
                </v-btn>
                <v-btn fab dark color="blue darken-3" class="ml-1" @click="nextPage">
                    <v-icon>mdi-chevron-right</v-icon>
                </v-btn>
                </v-row>
            </template>
            </v-data-iterator>
        </v-container>
        <v-row justify="center">
            <v-dialog v-model="userDialog" fullscreen hide-overlay transition="dialog-bottom-transition">
                <v-card>
                    <v-toolbar dark color="primary">
                        <v-btn icon dark @click="userDialog = false;createPost = [];">
                            <v-icon>mdi-close</v-icon>
                        </v-btn>
                        <v-spacer></v-spacer>
                        <v-toolbar-items>
                            <v-btn dark text  @click="userDeleteDialog = true">
                            delete
                            </v-btn>
                            <v-btn dark text @click="userDialog = false">
                            Save
                            </v-btn>
                        </v-toolbar-items>
                    </v-toolbar>
                    <v-layout :wrap="size">
                        <div>
                            <div class="py-5 px-5 d-flex align-center">
                                <v-avatar><v-img :src="'/storage/image/avatar/' + user.profile_image"></v-img></v-avatar>
                                <div class="pb-5 px-5">
                                    <h2>{{ user.name }}</h2>
                                    <h4>{{ user.screen_name }}</h4>
                                    <h4>{{ user.email }}</h4>
                                </div>
                            </div>
                            <v-list three-line subheader>
                                <v-subheader>posts</v-subheader>
                                <input ref="input" type="file" accept="image/jpeg, image/png" v-show="false" multiple @input="selectedFile()">
                                <div v-if="createPost.length !==0" style="margin: auto; max-width: 300px;width: 100%;">
                                    <v-row justify="space-around">
                                    <v-icon @click="createPostIndex--">
                                        mdi-minus
                                    </v-icon>
                                    {{ createPostIndex }}
                                    <v-icon @click="createPostIndex++">
                                        mdi-plus
                                    </v-icon>
                                    </v-row>
                                    <v-carousel style="width: 300px;height: 300px;" v-model="createPostIndex" hide-delimiters :show-arrows="false">
                                        <v-carousel-item v-for="image in createPost" :key="image" :src="image"></v-carousel-item>
                                    </v-carousel>
                                    <v-row justify="space-around">
                                    <div style="margin: auto;">{{ createPost.length }}</div>
                                    </v-row>
                                </div>

                                <v-btn @click="postCreate" v-if="createPost.length !==0">create</v-btn>
                                <v-btn @click="clickInput">select</v-btn>
                                <v-btn @click="userEdit">edit</v-btn>
                                <v-list-item v-for="(post, index) in posts" :key="index">
                                    <v-list-item-content>
                                    <v-list-item-title>{{ post.title }}</v-list-item-title>
                                    <v-list-item-subtitle>{{ post.text }}</v-list-item-subtitle>
                                    </v-list-item-content>
                                    <v-list-item-action>
                                    <v-row justify="space-around">
                                    <v-icon @click="carouselPrev(index)">
                                        mdi-minus
                                    </v-icon>
                                    {{ carousel[index] }}
                                    <v-icon @click="carouselNext(index)">
                                        mdi-plus
                                    </v-icon>
                                    </v-row>
                                    <v-carousel v-model="carousel[index]" width="50" height="50" hide-delimiters :show-arrows="false">
                                        <v-carousel-item width="50" v-for="(postImage, i) in JSON.parse(post.image)" :key="i" :src="'storage/image/' + postImage"></v-carousel-item>
                                    </v-carousel>
                                    <div style="margin: auto;">{{ JSON.parse(post.image).length }}</div>
                                    </v-list-item-action>
                                    <v-btn @click="deletePost(post)">delete</v-btn>
                                </v-list-item>
                            </v-list>
                        </div>
                        <div>
                            <v-list three-line subheader>
                                <v-subheader>comments</v-subheader>
                                <v-list-item v-for="(comment, index) in comments" :key="index">
                                    <v-list-item-content>
                                    <v-list-item-title>{{ comment.title }}</v-list-item-title>
                                    <v-list-item-subtitle>{{ comment.text }}</v-list-item-subtitle>
                                    </v-list-item-content>
                                    <v-list-item-action>
                                    <v-btn>delete</v-btn>
                                    </v-list-item-action>
                                </v-list-item>
                            </v-list>
                        </div>
                    </v-layout>
                </v-card>
            </v-dialog>
        </v-row>
        <v-row justify="center">
            <v-dialog v-model="userDeleteDialog" max-width="400">
            <v-card>
                <v-card-title class="headline">
                You are trying to delete this.
                </v-card-title>
                <v-card-text>Are you sure you are gonna delete it??</v-card-text>
                <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="green darken-1" text @click="userDeleteDialog = false">
                    No
                </v-btn>
                <v-btn color="green darken-1" text @click="userDelete(user.id)">
                    Yes
                </v-btn>
                </v-card-actions>
            </v-card>
            </v-dialog>
        </v-row>
        <v-row justify="center">
            <v-dialog v-model="profileDialog" max-width="600px">
            <v-card>
                <v-card-title>
                <span class="headline">プロフィール</span>
                </v-card-title>
                <v-card-text>
                <v-container>
                    <v-form ref="form" v-model="valid" lazy-validation>
                        <v-row>
                            <v-col cols="12" sm="6" md="6">
                                <v-text-field v-model="editUser.name" label="名前" required :rules="nameRules"></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6" md="6">
                                <v-text-field v-model="editUser.screen_name" label="ニックネーム" :rules="nicknameRules"></v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <v-text-field v-model="editUser.email" label="メールアドレス" required :rules="emailRules"></v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <input ref="avatar" type="file" @input="avatar">
                            </v-col>
                            <v-col cols="12">
                                <input ref="bg" type="file" @input="bg">
                            </v-col>
                        </v-row>
                    </v-form>
                </v-container>
                </v-card-text>
                <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="profileDialog = false;$refs.avatar.value = '';$refs.bg.value = ''">
                    Close
                </v-btn>
                    <v-btn color="blue darken-1" text @click="userUpdate">
                        Save
                    </v-btn>
                </v-card-actions>
            </v-card>
            </v-dialog>
        </v-row>
        <v-overlay :value="progress">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
    </div>
</template>

<script>
  export default {
    data () {
        return {
            progress: '',
            userDialog: false,
            userDeleteDialog: false,
            itemsPerPageArray: [4, 8, 12],
            search: '',
            filter: {},
            sortDesc: false,
            page: 1,
            itemsPerPage: 12,
            sortBy: 'id',
            keys: [
                'Screen_name',
                'Name',
                'Email',
            ],
            items: [],
            user: '',
            carousel: [],
            createPostIndex: '',
            createPost: [],
            windowSize: {
                x: 0,
                y: 0,
            },
            profileDialog: false,
            editUser: '',
            profile_image: '',
            bg_image: '',
            nameRules: [
                (v) => !!v || '入力が必要です',
            ],
            nicknameRules: [
                (v) => !!v || '入力が必要です',
            ],
            emailRules: [
                (v) => !!v || '入力が必要です',
                (v) => /.+@.+\..+/.test(v) || 'E-mail must be valid',
            ],
            valid: false,
        }
    },
    computed: {
        numberOfPages () {
            return Math.ceil(this.items.length / this.itemsPerPage)
        },
        filteredKeys () {
            return this.keys.filter(key => key !== 'Name')
        },
        posts (){
            return this.user.posts;
        },
        comments (){
            return this.user.comments;
        },
        size (){
            return this.windowSize.x < 480 ? true : false;
        }
    },
    created(){
        axios.get('/api/users')
        .then((response) => {
            this.items = response.data;
        })
        .catch((errors) => {
            console.log(errors);
        })
    },
    methods: {
        onResize () {
            this.windowSize = { x: window.innerWidth, y: window.innerHeight }
        },
        carouselNext(key){
            this.$set(this.carousel, key, this.carousel[key]+1)
        },
        carouselPrev(key){
            this.$set(this.carousel, key, this.carousel[key]-1)
        },
        nextPage () {
            if (this.page + 1 <= this.numberOfPages) this.page += 1
        },
        formerPage () {
            if (this.page - 1 >= 1) this.page -= 1
        },
        updateItemsPerPage (number) {
            this.itemsPerPage = number
        },
        openDialog(id){
            this.progress = true;
            axios.get(`/api/users/${id}`)
            .then((response) => {
                this.progress = false;
                this.userDialog = true;
                this.user = response.data;
            })
            .catch((error) => {
                this.progress = false;
                console.log('error')
            })
        },
        userCreate(){
            this.progress = true;
            axios.post('/api/users')
            .then((response) => {
                this.progress = false;
                this.items.push(response.data)
            })
            .catch((error) => {
                this.progress = false;
                console.log('error')
            })
        },
        userEdit(){
            this.progress = true;
            axios.get(`/api/users/${this.user.id}/edit`)
            .then((response) => {
                this.progress = false;
                this.profileDialog = true
                this.editUser = response.data;
            })
            .catch((error) => {
                this.progress = false;
                console.log('error')
            })
        },
        userUpdate(){
            this.progress = true;
            var params = {
                'id': this.user.id,'name': this.editUser.name,
                'screen_name': this.editUser.screen_name,
                'email': this.editUser.email,
                'profile_image': this.profile_image,
                'bg_image': this.bg_image,
            }
            axios.put(`/api/users/${this.editUser.id}`, params)
            .then(
                response => {
                    this.progress = false;
                    this.user.name = this.editUser.name;
                    this.user.screen_name = this.editUser.screen_name;
                    this.user.email = this.editUser.email;
                    this.user.profile_image = response.data.profile_image;
                    this.user.bg_image = response.data.bg_image;
                    var item = this.items.find((item) => {
                        return item.id === this.user.id;
                    })
                    item.profile_image = response.data.profile_image;
                    item.bg_image = response.data.bg_image;
                    this.profileDialog = false;
                    this.$refs.avatar.value = '';
                    this.$refs.bg.value = '';
                }
            )
            .catch((error) => {
                this.progress = false;
                console.log(error)
            });
        },
        userDelete(userId){
            this.progress = true;
            axios.delete(`/api/users/${userId}`, {data: {id: userId}})
            .then((response) => {
                this.progress = false;
                var index = this.items.findIndex(({id}) => id === userId);
                this.userDialog = false;
                this.userDeleteDialog = false;
                this.items.splice(index, 1);
            })
            .catch((error) => {
                this.progress = false;
                console.log('error')
            })
        },
        postCreate(){
            this.progress = true;
            var param = {
                user_id: this.user.id,
                image: this.createPost,
            }
            axios.post('/api/posts', param)
            .then((response) => {
                this.progress = false;
                var index = this.items.findIndex(({id}) => id === this.user.id);
                this.$set(this.user.posts, (this.user.posts.length), response.data);
                this.items[index].posts_count += 1;
                this.createPost = [];
            })
            .catch((error) => {
                this.progress = false;
                console.log('error')
            })
        },
        clickInput() {
            this.$refs.input.click();
        },
        async selectedFile(){
            this.progress = true;
            const files = this.$refs.input.files;
            var postArray = [];
            for (var i = 0; i < files.length; i++) {
                const reader = new FileReader();
                let that = this;
                reader.onload = (function (event) {
                    postArray.unshift(event.target.result);
                    that.progress = false;
                });
                reader.readAsDataURL(files[i]);
            }
            this.createPost = postArray;
        },
        deletePost(post){
            this.progress = true;
            axios.delete(`/api/posts/${post.id}`, {data: {id: post.id}})
            .then((response) => {
                this.progress = false;
                var index = this.user.posts.findIndex(({id}) => id === post.id);
                this.user.posts.splice(index, 1);
                this.items[index].posts_count -= 1;
            })
            .catch((error) => {
                this.progress = false;
                console.log('error')
            })
        },
        avatar(ev){
            const file = ev.target.files[0];
            let reader = new FileReader();
            reader.onload = (e) => {
                this.profile_image = e.target.result;
            };
            reader.readAsDataURL(file);
        },
        bg(ev){
            const file = ev.target.files[0];
            let reader = new FileReader();
            reader.onload = (e) => {
                this.bg_image = e.target.result;
            };
            reader.readAsDataURL(file);
        },
    },
}
</script>
<style scoped>
.col, .col-1, .col-2, .col-3, .col-4, .col-5, .col-6, .col-7, .col-8, .col-9, .col-10, .col-11, .col-12, .col-auto, .col-lg, .col-lg-1, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg-auto, .col-md, .col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12, .col-md-auto, .col-sm, .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm-auto, .col-xl, .col-xl-1, .col-xl-2, .col-xl-3, .col-xl-4, .col-xl-5, .col-xl-6, .col-xl-7, .col-xl-8, .col-xl-9, .col-xl-10, .col-xl-11, .col-xl-12, .col-xl-auto {
    width: 100%;
    padding: 4px;
}
.row {
    margin: 0;
}
</style>
