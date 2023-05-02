<template>
  <div>
         <Accessoriesfashion v-if="sortsection.sectionname == 'accessories' && sortsection.status == 1" :data="data"></Accessoriesfashion>
         <Logo v-if="sortsection.sectionname == 'logo' && sortsection.status == 1" :data="data"></Logo>
         <Collection :collections="data.collections" :auth="auth" v-if="sortsection.sectionname == 'collection' && sortsection.status == 1" :data="data"></Collection>
         <Trends v-if="sortsection.sectionname == 'besttrends' && sortsection.status == 1" :data="data"></Trends>
         <NewArriaval :newArriaval="data.newArriaval" :auth="auth" v-if="sortsection.sectionname == 'newarriaval' && sortsection.status == 1" :data="data"></NewArriaval>
  </div>
</template>

<script>
import Accessoriesfashion from './AccesoriesFashion';
import Logo from './Logo'
import Collection from './Collection'
import Trends from './Trends'
import NewArriaval from './NewArriaval'
import Latest from './Latest'
import { mapState } from 'vuex'

  export default {
    name: "homepage",
    props: ['data', 'auth','sortsection'],
    data() {
      return {
        cruds: []
      }
    },
    computed: {
      
    },
    methods: {
     
    },
    components: { 
      Accessoriesfashion,  Logo,  Collection,  Trends,  NewArriaval,  Latest,
    }
  }
</script>