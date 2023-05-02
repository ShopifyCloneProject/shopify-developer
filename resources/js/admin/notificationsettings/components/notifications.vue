<template>
    <div class="row">
        <div class="col-12">
            <div class="card" v-for="(item, categorykey) in list.categories">
                <div class="card-header">
                    <h4>{{ item }}</h4>
                </div>
                <div class="card-body">
                    <div class="row border-bottom mt-1" v-for="(item, key) in data.notifications" v-if="categorykey == item.category">
                        <div class="col-3">
                            <a  :href="'/admin/settings/notificationdetails/template/'+item.id" 
                                class="notification-title"
                                >
                                {{item.title}}
                            </a>
                        </div>
                        <div class="col-9">
                            <p>{{item.description}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
   
</template>
<script>
    import { mapGetters, mapActions } from 'vuex'

    export default {
        props: ['list', 'data', 'type'],
        name:'notifications',
        data() {
            return {
            }
        },
        mounted(){
            
        },
        components: {

        },
        computed: {
        },
        created() {
        },
        methods: {
          
            
         
        }
    }
</script>

<style scoped>
    .store-content p {
        font-size: 13px;
        text-align: justify;
    }
    .store-title {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 20px;
    }
    .notification-title{
        text-decoration: underline;
        font-weight: bold;
    }
</style>