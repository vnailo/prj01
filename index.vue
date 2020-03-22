<template>

                                                            <autocomplete
                                                                v-model="value1"
                                                                :options="{'class': 'form-control-sm', minLength: 3}"
                                                                :onInputChange="onInputChange"
                                                                :onItemSelected="onItemSelected"
                                                                class=""
                                                                placeholder="Введіть  ..."
                                                            ></autocomplete>

<p>{{ value2 }}</p>

</template>
 <script>

import $ from 'jquery';

//import axios from 'axios';

import AutoComplete from './autocomplete'


export default {
    components: {
                'autocomplete': AutoComplete
    },
    props: {

    },
    data() {

        return {
            value1: "",
            value2: ""
        }
    },
    mounted(){

    },
    methods: {
        prepRespondArray: function(arr) {
            return arr.map((item, index) => {
                if (item.label) {
                    return (item)
                } else {
                    return ({value:index, label:item})
                }
            })
        },

        onInputChange (query) {
            this.value1 = '';
            if (query.trim().length  < 3) {
                return null
            }

     //       return this.data.filter((item) => {
     //           return item.label.toLowerCase().includes(query.toLowerCase())
     //       })



            return new Promise(function (resolve, reject) {
                        $.ajax({
                            method: "POST",
                            url: 'http://localhost/api/',
                    //      dataType: "jsonp",
                            data: {
                                method: 'value1',
                                term: query,
                                limit: 300,
                                _: new Date().getTime()
                            }
                        })
                        .done(function(respond){
                            var items = [];
                            if(respond.length > 0) {
                                items = respond;
                            } else {
                                items = [{ label: 'No results found.', value: -1}];
                            }
                            resolve(items);
                         })
                        .fail(reject)
            });
        },
        onItemSelected (item) {
            if ( item.value != -1) {
                this.value1 = item.label;
                this.value2 = item.value;
            } else {
                this.value1 = '';
                this.value2 = '';
            }
        },
    },
    computed: {
    },
    watch: {


    },

}

</script>
<style>

</style>

