<template atoms functional>
    <v-container>
        <input style="display: none" ref="input" type="file" accept="image/jpeg, image/jpg, image/png" @input="listeners.input">
        <v-layout text-xs-center wrap>
            <v-flex xs12>
                <input id="select" type="image" src="/image/select-image.png" @click="listeners.click">
            </v-flex>
        </v-layout>
    </v-container>
</template>